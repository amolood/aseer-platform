<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleComment;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Detainee;
use App\Models\DetaineePhoto;
use App\Models\Page;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FrontController extends Controller
{

    public function index(Request $request)
    {
        return view('front.index');
    }

    // عرض الأسرى المعتمدين فقط
    public function detainees(Request $request)
    {
        $detainees = Detainee::where('is_approved', true)
            ->when($request->search, fn ($q) => $q->where('name', 'like', '%' . $request->search . '%'))
            ->when($request->location, fn ($q) => $q->where('location', $request->location))
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->date, fn ($q) => $q->whereDate('detention_date', $request->date))
//            ->where('status', '!=', 'martyr')
            ->with('photos')
            ->orderByDesc('id')
            ->paginate(20);

        $locations = Detainee::where('is_approved', true)
            ->whereNotNull('location')
            ->distinct()
            ->pluck('location');

        return view('front.pages.detainees', compact('detainees', 'locations'));
    }


    // عرض بيانات أسير مفصل
    public function detainee_show($id)
    {
        $detainee = Detainee::findOrFail($id);

        if (! $detainee->is_approved && $detainee->user_id !== auth()->id()) {
            abort(404);
        }


        // جلب أسرى مشابهين بنفس الموقع أو الحالة
        $relatedDetainees = Detainee::where('is_approved', true)
            ->where('id', '!=', $detainee->id)
            ->where(function ($query) use ($detainee) {
                $query->where('location', $detainee->location)
                    ->orWhere('status', $detainee->status);
            })
            ->inRandomOrder()
            ->limit(4)
            ->get();
        // جلب صور الأسير
        $photos = DetaineePhoto::where('detainee_id', $detainee->id)->get();

        return view('front.pages.detainee-show', compact('detainee', 'relatedDetainees', 'photos'));
    }

    // نموذج إرسال بيانات أسير من قبل الزوار
    public function detainee_create()
    {
        return view('front.pages.detainee-create');
    }

    // store detainee data
    public function detainee_store(Request $request)
    {
        if (! $request->isMethod('post')) {
            abort(405); // Method Not Allowed
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'nullable|in:male,female',
            'birth_date' => 'nullable|date',
            'location' => 'nullable|string|max:255',
            'detention_date' => 'nullable|date',
            'status' => 'required|in:detained,missing,released,martyr',
            'detaining_authority' => 'nullable|string|max:255',
            'prison_name' => 'nullable|string|max:255',
            'is_forced_disappearance' => 'nullable|boolean',
            'family_contact_name' => 'nullable|string|max:255',
            'family_contact_phone' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'source' => 'nullable|string|max:255',
            'photos' => 'nullable|array',
            'photos.*' => 'image|max:2048',
        ]);

        $data['is_approved'] = false;

        $detainee = Auth::user()->detainees()->create($data);

        // store photos and mark first one as featured
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $index => $photo) {
                $path = $photo->store('detainees', 'public');

                DetaineePhoto::create([
                    'detainee_id' => $detainee->id,
                    'path' => $path,
                    'is_featured' => ! $detainee->photos()->exists() && $index === 0,
                ]);
            }
        }

        if (auth()->check()) {
            $user = auth()->user();

            // تأكد من عدم تكرار المتابعة
            $detainee->followers()->syncWithoutDetaching([$user->id]);

            return view('front.pages.thanks');
        } else {
            return redirect()->back()->with('error', 'يجب تسجيل الدخول لإضافة أسير.');
        }
    }

    // نموذج تعديل بيانات أسير
    public function detainee_edit($id)
    {
        $detainee = Detainee::findOrFail($id);

        // Check if the user is authorized to edit this detainee
        if ($detainee->user_id !== auth()->id()) {
            abort(403, 'غير مصرح لك بتعديل بيانات هذا الأسير.');
        }

        $photos = DetaineePhoto::where('detainee_id', $detainee->id)->get();

        return view('front.pages.detainee-edit', compact('detainee', 'photos'));
    }

    // تحديث بيانات الأسير
    public function detainee_update(Request $request, $id)
    {
        if (! $request->isMethod('put')) {
            abort(405); // Method Not Allowed
        }

        $detainee = Detainee::findOrFail($id);

        // Check if the user is authorized to update this detainee
        if ($detainee->user_id !== auth()->id()) {
            abort(403, 'غير مصرح لك بتعديل بيانات هذا الأسير.');
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'nullable|in:male,female',
            'birth_date' => 'nullable|date',
            'location' => 'nullable|string|max:255',
            'detention_date' => 'nullable|date',
            'status' => 'required|in:detained,missing,released,martyr',
            'detaining_authority' => 'nullable|string|max:255',
            'prison_name' => 'nullable|string|max:255',
            'is_forced_disappearance' => 'nullable|boolean',
            'family_contact_name' => 'nullable|string|max:255',
            'family_contact_phone' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'source' => 'nullable|string|max:255',
            'photos' => 'nullable|array',
            'photos.*' => 'image|max:2048',
            'deleted_photos' => 'nullable|string',
        ]);

        $detainee->update($data);

        // Handle deleted photos
        if ($request->filled('deleted_photos')) {
            $deletedPhotoIds = explode(',', $request->deleted_photos);
            foreach ($deletedPhotoIds as $photoId) {
                $photo = DetaineePhoto::find($photoId);
                if ($photo && $photo->detainee_id === $detainee->id) {
                    // Delete the file from storage
                    if (Storage::disk('public')->exists($photo->path)) {
                        Storage::disk('public')->delete($photo->path);
                    }
                    $photo->delete();
                }
            }
        }

        // store new photos
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('detainees', 'public');

                DetaineePhoto::create([
                    'detainee_id' => $detainee->id,
                    'path' => $path,
                    'is_featured' => ! $detainee->photos()->exists(),
                ]);
            }
        }

        return redirect()->route('front.detainees.show', $detainee->id)
            ->with('success', 'تم تحديث بيانات الأسير بنجاح.');
    }

    public function detainee_delete($id)
    {
        $detainee = Detainee::findOrFail($id);

        // Check if the user is authorized to delete this detainee
        if ($detainee->user_id !== auth()->id()) {
            abort(403, 'غير مصرح لك بحذف بيانات هذا الأسير.');
        }

        // Delete associated photos from storage
        foreach ($detainee->photos as $photo) {
            if (Storage::disk('public')->exists($photo->path)) {
                Storage::disk('public')->delete($photo->path);
            }
        }

        // Delete the detainee
        $detainee->delete();

        toastr()->success('تم حذف الأسير بنجاح');

        return redirect('/dashboard/detainees');
    }

    public function comment_post(Request $request)
    {
        if (auth()->check()) {
            $request->validate([
                "content" => "required|min:3|max:10000",
            ]);
            ArticleComment::create([
                'user_id' => auth()->user()->id,
                'article_id' => $request->article_id,
                'content' => $request->content,
            ]);
        } else {
            $request->validate([
                'adder_name' => "nullable|min:3|max:190",
                'adder_email' => "nullable|email",
                "content" => "required|min:3|max:10000",
            ]);
            ArticleComment::create([
                'article_id' => $request->article_id,
                'adder_name' => $request->adder_name,
                'adder_email' => $request->adder_email,
                'content' => $request->content,
            ]);
        }
        toastr()->success("تم اضافة تعليقك بنجاح وسيظهر للعامة بعد المراجعة");

        return redirect()->back();
    }

    public function contact_post(Request $request)
    {
        $request->validate([
            'name' => "required|min:3|max:190",
            'email' => "nullable|email",
            "phone" => "required|numeric",
            "message" => "required|min:3|max:10000",
        ]);
        //if(\MainHelper::recaptcha($request->recaptcha)<0.8)abort(401);
        Contact::create([
            'user_id' => auth()->check()?auth()->id():null,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'message' => /*"قادم من : ".urldecode(url()->previous())."\n\nالرسالة : ".*/$request->message,
        ]);

        toastr()->success('تم استلام رسالتك بنجاح وسنتواصل معك في أقرب وقت');

        return redirect()->back();
    }
    public function category(Request $request, Category $category)
    {
        $articles = Article::where(function ($q) use ($request, $category) {
            if ($request->user_id != null) {
                $q->where('user_id', $request->user_id);
            }

            $q->whereHas('categories', function ($q) use ($request, $category) {
                $q->where('category_id', $category->id);
            });
        })->with(['categories','tags'])->withCount(['comments' => function ($q) {$q->where('reviewed', 1);}])->orderBy('id', 'DESC')->paginate();

        return view('front.pages.blog', compact('articles', 'category'));
    }
    public function tag(Request $request, Tag $tag)
    {

        $articles = Article::where(function ($q) use ($request, $tag) {
            if ($request->user_id != null) {
                $q->where('user_id', $request->user_id);
            }

            $q->whereHas('tags', function ($q) use ($request, $tag) {
                $q->where('tag_id', $tag->id);
            });
        })->with(['categories','tags'])->withCount(['comments' => function ($q) {$q->where('reviewed', 1);}])->orderBy('id', 'DESC')->paginate();

        return view('front.pages.blog', compact('articles', 'tag'));
    }
    public function article(Request $request, Article $article)
    {
        $article->load(['categories','comments' => function ($q) {$q->where('reviewed', 1);},'tags'])->loadCount(['comments' => function ($q) {$q->where('reviewed', 1);}]);
        $this->views_increase_article($article);

        return view('front.pages.article', compact('article'));
    }
    public function page(Request $request, Page $page)
    {

        $customView = 'front.pages.custom-pages.' . $page->slug;

        if (view()->exists($customView)) {
            // If the file exists, return custom page
            return view($customView, compact('page'));
        }

        return view('front.pages.page', compact('page'));
    }
    public function blog(Request $request)
    {
        $articles = Article::where(function ($q) use ($request) {
            if ($request->category_id != null) {
                $q->where('category_id', $request->category_id);
            }
            if ($request->user_id != null) {
                $q->where('user_id', $request->user_id);
            }
        })->with(['categories','tags'])->withCount(['comments' => function ($q) {$q->where('reviewed', 1);}])->orderBy('id', 'DESC')->paginate();

        return view('front.pages.blog', compact('articles'));
    }
    public function views_increase_article(Article $article)
    {
        $data = [
            'ip' => \UserSystemInfoHelper::get_ip(),
            'prev_link' => \UserSystemInfoHelper::prev_url(),
            'agent_name' => request()->header('User-Agent'),
            'browser' => \UserSystemInfoHelper::get_browsers(),
            'device' => \UserSystemInfoHelper::get_device(),
            'operating_system' => \UserSystemInfoHelper::get_os(),
        ];
        \App\Jobs\ItemSeenInsertJob::dispatch("\App\Models\Article", $article->id, $data);
    }
    public function builder(Request $request)
    {
        return view('front.pages.builder');
    }

}
