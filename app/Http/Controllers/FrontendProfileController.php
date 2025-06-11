<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\CarPhoto;
use App\Models\Detainee;
use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\ContactReply;

class FrontendProfileController extends Controller
{
    public function dashboard(Request $request)
    {
        $detainees = Detainee::where('user_id', auth()->id())
            ->withCount(['seenReports', 'errorReports', 'followers'])
            ->with(['photos']) // لو عايز الصور
            ->latest()
            ->paginate(12);

        return view('front.user.index', compact('detainees'));
    }
    public function balances(Request $request)
    {
        return view('front.user.balances');
    }
    public function support(Request $request)
    {
        return view('front.user.support');
    }
    public function create_ticket(Request $request)
    {
        return view('front.user.create-ticket');
    }
    public function store_ticket(Request $request)
    {
        $ticket = \App\Models\Contact::create([
            'user_id'=>auth()->user()->id,
            'name'=>auth()->user()->name,
            'email'=>auth()->user()->email,
            'message'=>$request->message
        ]);
        if($request->files !=null){
            foreach($request->files as $file){
                $ticket->addMedia($file)->toMediaCollection();
            }
        }
        return redirect()->route('user.ticket',$ticket);
    }

    public function edit($id)
    {
        $detainee = Detainee::where('user_id', auth()->id())->findOrFail($id);

        return view('front.user.edit', compact('detainee'));
    }

    public function ticket(Request $request,Contact $ticket)
    {
        return view('front.user.ticket',compact('ticket'));
    }
    public function reply_ticket(Request $request)
    {
        $request->validate([
            'message'=>"required|string|min:10|max:1000",
        ]);
        $ticket = \App\Models\Contact::where('user_id',auth()->user()->id)->where('id',$request->ticket_id)->firstOrFail();
        ContactReply::create([
            'user_id'=>auth()->user()->id,
            'is_support_reply'=>0,
            'contact_id'=>$ticket->id,
            'content'=>$request->message
        ]);
        return redirect()->back()->with([
            'message'=>"تم ارسال رسالتك بنجاح",
            'alert-type'=>"warning"
        ]);

    }
    public function notifications(Request $request)
    {
        return view('front.user.notifications');
    }
    public function profile_edit(Request $request)
    {
        return view('front.user.settings');
    }
    public function profile_update(Request $request)
    {
        return view('front.user.index');
    }
    public function profile_update_password(Request $request)
    {
        return view('front.user.index');
    }
    public function profile_update_email(Request $request)
    {
        return view('front.user.index');
    }

    public function cars_dashboard()
    {
        $cars = Car::where('user_id', auth()->id())
            ->with('photos')
            ->latest()
            ->paginate(20);

        return view('front.user.cars.index', compact('cars'));
    }

    public function car_show(Car $car)
    {
        if ($car->user_id !== auth()->id()) {
            abort(403);
        }

        return view('front.user.cars.show', compact('car'));
    }

    public function car_edit(Car $car)
    {
        if ($car->user_id !== auth()->id()) {
            abort(403);
        }

        return view('front.user.cars.edit', compact('car'));
    }

    public function car_update(Request $request, Car $car)
    {
        if ($car->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'manufacturer' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'license_plate' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'missing_date' => 'nullable|date',
            'status' => 'required|in:missing,found,stolen',
            'owner_name' => 'nullable|string|max:255',
            'owner_contact' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
            'photos' => 'nullable|array',
            'photos.*' => 'image|max:8048',
            'featured_photo' => 'nullable|exists:car_photos,id'
        ]);

         // Handle photo uploads
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('cars', 'public');
                CarPhoto::create([
                    'car_id' => $car->id,
                    'path' => $path,
                    'is_featured' => false, // Set initially to false
                ]);
            }
        }

        // Handle featured photo
        if ($request->filled('featured_photo')) {
            // If a specific featured photo is selected
            $car->photos()->update(['is_featured' => false]);
            CarPhoto::where('id', $request->featured_photo)->update(['is_featured' => true]);
        } else {
            // Auto set featured photo if none exists
            if (!$car->photos()->where('is_featured', true)->exists()) {
                // Get the first photo (either newly uploaded or existing)
                $firstPhoto = $car->photos()->first();
                if ($firstPhoto) {
                    $firstPhoto->update(['is_featured' => true]);
                }
            }
        }

        $car->update($validated);
        toastr()->success('تم تحديث بيانات السيارة بنجاح');

        return redirect()->route('user.cars.show', $car->id);
    }

    public function car_create()
    {
        return view('front.user.cars.create');
    }

    public function car_store(Request $request)
    {
        $validated = $request->validate([
            'manufacturer' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'license_plate' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'missing_date' => 'nullable|date',
            'status' => 'required|in:missing,found,stolen',
            'owner_name' => 'nullable|string|max:255',
            'owner_contact' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
            'photos' => 'required|array',
            'photos.*' => 'image|max:8048'
        ]);

        $validated['user_id'] = auth()->id();
        $validated['is_approved'] = false;

        $car = Car::create($validated);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('cars', 'public');
                CarPhoto::create([
                    'car_id' => $car->id,
                    'path' => $path,
                    'is_featured' => !$car->photos()->exists()
                ]);
            }
        }

        toastr()->success('تم إضافة السيارة بنجاح وهي في انتظار المراجعة');
        return redirect()->route('user.cars.show', $car->id);
    }
}
