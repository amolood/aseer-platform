<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\CarPhoto;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class BackendCarController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:cars-read', ['only' => ['index']]);
        $this->middleware('can:cars-update', ['only' => ['approve', 'edit', 'update']]);
        $this->middleware('can:cars-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $locations = Car::whereNotNull('location')
            ->distinct()
            ->pluck('location')
            ->filter()
            ->values();

        $cars = Car::query()
            ->when($request->q, fn($q) => $q->where('license_plate', 'like', '%' . $request->q . '%')
                ->orWhere('manufacturer', 'like', '%' . $request->q . '%')
                ->orWhere('model', 'like', '%' . $request->q . '%'))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->is_approved !== null, fn($q) => $q->where('is_approved', $request->is_approved))
            ->when($request->location, fn($q) => $q->where('location', 'like', '%' . $request->location . '%'))
            ->when($request->date_from, fn($q) => $q->whereDate('missing_date', '>=', $request->date_from))
            ->when($request->date_to, fn($q) => $q->whereDate('missing_date', '<=', $request->date_to))
            ->latest()
            ->paginate(20);

        return view('admin.cars.index', compact('cars', 'locations'));
    }

    public function show(Car $car)
    {
        return view('admin.cars.show', compact('car'));
    }

    public function edit(Car $car)
    {
        return view('admin.cars.edit', compact('car'));
    }

    public function update(Request $request, Car $car)
    {
        $validated = $request->validate([
            'license_plate' => 'nullable|string|max:255',
            'manufacturer' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'color' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'missing_date' => 'nullable|date',
            'status' => 'nullable|in:missing,found,stolen',
            'owner_name' => 'nullable|string|max:255',
            'owner_contact' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
            'source' => 'nullable|string|max:255',
            'is_approved' => 'nullable|boolean',
            'photos' => 'nullable|array',
            'photos.*' => 'image|max:8048',
            'featured_photo' => 'nullable|exists:car_photos,id'
        ]);

        // Track changed fields
        $updatedFields = [];
        foreach ($validated as $key => $newValue) {
            if ($key === 'photos' || $key === 'featured_photo') {
                continue;
            }

            $oldValue = $car->$key;
            if ($key === 'missing_date' && $oldValue && $newValue) {
                $oldValue = Carbon::parse($oldValue)->toDateString();
                $newValue = Carbon::parse($newValue)->toDateString();
            }

            if ($newValue !== null && $newValue != $oldValue) {
                $updatedFields[$key] = $newValue;
            }
        }

        // Handle photo uploads
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('cars', 'public');
                CarPhoto::create([
                    'car_id' => $car->id,
                    'path' => $path,
                ]);
            }
        }

        // Handle featured photo
        if ($request->filled('featured_photo')) {
            $car->photos()->update(['is_featured' => false]);
            CarPhoto::where('id', $request->featured_photo)->update(['is_featured' => true]);
        }
        else if ($car->photos()->count() > 0 && !$car->photos()->where('is_featured', true)->exists()) {
            $car->photos()->first()->update(['is_featured' => true]);
        }

        if (!empty($updatedFields)) {
            $car->update($updatedFields);
            toastr()->success('تم تحديث البيانات المتغيرة فقط');
        }

        return redirect()->route('admin.cars.index')->with('success', 'تم تحديث السيارة بنجاح');
    }

    public function approve(Car $car)
    {
        $car->update(['is_approved' => true]);
        toastr()->success('تمت الموافقة على السيارة بنجاح');
        return back();
    }

    public function unapprove($id)
    {
        $car = Car::findOrFail($id);
        $car->is_approved = false;
        $car->save();
        return redirect()->back()->with('success', 'تم إلغاء الموافقة على السيارة.');
    }

    public function deletePhoto(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:car_photos,id',
        ]);

        $photo = CarPhoto::findOrFail($request->id);
        if (Storage::disk('public')->exists(str_replace('/storage/', '', $photo->url))) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $photo->url));
        }
        $photo->delete();

        return response()->json(['success' => true]);
    }

    public function destroy(Car $car)
    {
        $car->delete();
        toastr()->success('تم حذف السيارة بنجاح');
        return back();
    }
}
