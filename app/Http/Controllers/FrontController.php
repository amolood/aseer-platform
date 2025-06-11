<?php

namespace App\Http\Controllers;

use App\Models\Detainee;
use App\Models\DetaineePhoto;
use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Tag;
use App\Models\Contact;
use App\Models\ArticleComment;
use App\Models\Page;
use App\Models\Category;
use App\Models\Car;
use App\Models\CarPhoto;

class FrontController extends Controller
{

    public function index(Request $request)
    {
        return view('front.index');
    }

    // عرض الأسرى المعتمدين فقط
    public function detainees(Request $request)
    {
        $allStatuses = Detainee::where('is_approved', true)
            ->select('status', \DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $total = array_sum($allStatuses);

        // دمج total داخل المصفوفة نفسها
        $allStatuses['total'] = $total;

        $detainees = Detainee::where('is_approved', true)
            ->when($request->search, fn($q) => $q->where('name', 'like', '%' . $request->search . '%'))
            ->when($request->location, fn($q) => $q->where('location', $request->location))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->with('photos')
            ->orderByDesc('id')
            ->paginate(20);

        $locations = Detainee::where('is_approved', true)
            ->whereNotNull('location')
            ->distinct()
            ->pluck('location');

        return view('front.pages.detainees', compact('detainees', 'locations', 'allStatuses'));
    }


    // عرض بيانات أسير مفصل
    public function detainee_show($id)
    {
        $detainee = Detainee::findOrFail($id);

        if (! $detainee->is_approved && $detainee->user_id !== auth()->id()) {
            abort(404);
        }
        $page_image = $detainee->photos()->where('is_featured', true)->first()->url ?? null;
        if($detainee->status == 'detained') {
           $detaineeDesc = ' معتقل في ' . $detainee->location;
        } elseif ($detainee->status == 'missing') {
            $detaineeDesc = ' مفقود في ' . $detainee->location;
        } elseif ($detainee->status == 'kidnapped') {
            $detaineeDesc = ' مختطف في ' . $detainee->location;
        } elseif ($detainee->status == 'released') {
            $detaineeDesc = ' محرر في ' . $detainee->location;
        } elseif ($detainee->status == 'forced_disappearance') {
            $detaineeDesc = ' مختفي قسريًا في ' . $detainee->location;
        }  elseif ($detainee->status == 'missing') {
            $detaineeDesc = ' مفقود في ' . $detainee->location;
        } elseif ($detainee->status == 'detained') {
            $detaineeDesc = 'أسير معتقل في ' . $detainee->location;
        } else {
            $detaineeDesc = ' أسير في ' . $detainee->location;
        };
        $page_description = $detaineeDesc . ' منذ ' . $detainee->detention_date;

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

        $detainee = Detainee::where('is_approved', true)->findOrFail($id);
        return view('front.pages.detainee-show', compact('detainee', 'relatedDetainees', 'photos','page_image','page_description'));
    }

    // نموذج إرسال بيانات أسير من قبل الزوار
    public function detainee_create()
    {
        return view('front.pages.detainee-create');
    }

    // store detainee data
    public function detainee_store(Request $request)
    {
        if (!$request->isMethod('post')) {
            abort(405); // Method Not Allowed
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'nullable|in:male,female',
            'birth_date' => 'nullable|date',
            'location' => 'nullable|string|max:255',
            'detention_date' => 'nullable|date',
            'status' => 'required|in:detained,missing,released,martyr,kidnapped',
            'detaining_authority' => 'nullable|string|max:255',
            'prison_name' => 'nullable|string|max:255',
            'is_forced_disappearance' => 'nullable|boolean',
            'family_contact_name' => 'nullable|string|max:255',
            'family_contact_phone' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'source' => 'nullable|string|max:255',
            'photos' => 'required|array',
            'photos.*' => 'image|max:8048',
        ]);

        $data['detaining_authority'] = str_replace('مليشيا','',$request->detaining_authority);

        $data['is_approved'] = false;

        $detainee = Detainee::create($data);

        // store photos and mark first one as featured
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $index => $photo) {
                $path = $photo->store('detainees', 'public');

                DetaineePhoto::create([
                    'detainee_id' => $detainee->id,
                    'path' => $path,
                    'is_featured' => !$detainee->photos()->exists() && $index === 0,
                ]);
            }
        }

        if (auth()->check()) {
            $user = auth()->user();

            // تأكد من عدم تكرار المتابعة
            $detainee->followers()->syncWithoutDetaching([$user->id]);

            // تعيين المستخدم كأساس لهذا الأسير
            $detainee->user_id = $user->id;
            $detainee->save();

            return view('front.pages.thanks');
        } else {
            return redirect()->back()->with('error', 'يجب تسجيل الدخول لإضافة أسير.');
        }
    }

    public function cars(Request $request)
    {
        $allStatuses = Car::where('is_approved', true)
            ->select('status', \DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $total = array_sum($allStatuses);
        $allStatuses['total'] = $total;

        $cars = Car::where('is_approved', true)
            ->when($request->search, fn($q) => $q->where('manufacturer', 'like', '%' . $request->search . '%')
                ->orWhere('model', 'like', '%' . $request->search . '%')
                ->orWhere('license_plate', 'like', '%' . $request->search . '%'))
            ->when($request->location, fn($q) => $q->where('location', $request->location))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->with('photos')
            ->orderByDesc('id')
            ->paginate(20);

        $locations = Car::where('is_approved', true)
            ->whereNotNull('location')
            ->distinct()
            ->pluck('location');

        return view('front.pages.cars', compact('cars', 'locations', 'allStatuses'));
    }

    public function car_show($id)
    {
        $car = Car::where('is_approved', true)->findOrFail($id);

        // Get similar cars based on location or status
        $relatedCars = Car::where('is_approved', true)
            ->where('id', '!=', $car->id)
            ->where(function ($query) use ($car) {
                $query->where('location', $car->location)
                    ->orWhere('status', $car->status);
            })
            ->inRandomOrder()
            ->limit(4)
            ->get();

        $photos = $car->photos;
        $page_image = optional($car->photos()->where('is_featured', true)->first())->url;
        $page_description = sprintf('%s %s %s في %s منذ %s',
            $car->manufacturer,
            $car->model,
            __('car_status.' . $car->status),
            $car->location,
            $car->missing_date ? $car->missing_date->format('Y-m-d') : 'تاريخ غير معروف'
        );

        return view('front.pages.car-show', compact('car', 'relatedCars', 'photos', 'page_image', 'page_description'));
    }

    public function car_create()
    {
        return view('front.pages.car-create');
    }

    public function car_store(Request $request)
    {
        if (!$request->isMethod('post')) {
            abort(405);
        }

        $data = $request->validate([
            'license_plate' => 'nullable|string|max:255',
            'manufacturer' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'color' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'missing_date' => 'nullable|date',
            'status' => 'required|in:missing,found,stolen',
            'owner_name' => 'nullable|string|max:255',
            'owner_contact' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
            'source' => 'nullable|string|max:255',
            'photos' => 'required|array',
            'photos.*' => 'image|max:8048',
        ]);

        $data['is_approved'] = false;

        $car = Car::create($data);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $index => $photo) {
                $path = $photo->store('cars', 'public');

                CarPhoto::create([
                    'car_id' => $car->id,
                    'path' => $path,
                    'is_featured' => !$car->photos()->exists() && $index === 0,
                ]);
            }
        }

        if (auth()->check()) {
            $car->user_id = auth()->id();
            $car->save();
        }

        return view('front.pages.thanks');
    }

    public function reportCar(Request $request, Car $car)
    {
        $request->validate([
            'details' => 'required|string',
            'contact_info' => 'nullable|string|max:255',
            'report_type' => 'required|in:sighting,error', // Optional, defaults to 'sighting'
            'location' => 'required_if:report_type,==,sighting"|string|max:255',
        ]);

        $car->reports()->create([
            'user_id' => auth()->id(),
            'location' => $request->location,
            'details' => $request->details,
            'contact_info' => $request->contact_info,
            'report_type' => $request->report_type ?? 'sighting', // Default to 'sighting' if not provided
        ]);

        return back()->with('success', 'تم إرسال البلاغ، شكرًا لمساهمتك.');
    }

    public function reportSeen(Request $request, Detainee $detainee)
    {
        $request->validate([
            'location' => 'required|string|max:255',
            'details' => 'nullable|string',
            'contact' => 'nullable|string|max:255',
        ]);

        $detainee->seenReports()->create([
            'user_id' => auth()->id(),
            'location' => $request->location,
            'details' => $request->details,
            'contact' => $request->contact,
        ]);

        return back()->with('success', 'تم إرسال البلاغ، شكرًا لمساهمتك.');
    }

    public function reportError(Request $request, Detainee $detainee)
    {
        $request->validate([
            'details' => 'required|string',
            'contact_info' => 'nullable|string|max:255',
        ]);

        $detainee->errorReports()->create([
            'user_id' => auth()->id(),
            'details' => $request->details,
            'contact_info' => $request->contact_info,
        ]);

        return back()->with('success', 'تم إرسال الملاحظة، سيتم مراجعتها من الإدارة.');
    }

    public function comment_post(Request $request)
    {
        if(auth()->check()){
            $request->validate([
                "content"=>"required|min:3|max:10000",
            ]);
            ArticleComment::create([
                'user_id'=>auth()->user()->id,
                'article_id'=>$request->article_id,
                'content'=> $request->content,
            ]);
        }else{
            $request->validate([
                'adder_name'=>"nullable|min:3|max:190",
                'adder_email'=>"nullable|email",
                "content"=>"required|min:3|max:10000",
            ]);
            ArticleComment::create([
                'article_id'=>$request->article_id,
                'adder_name'=>$request->adder_name,
                'adder_email'=>$request->adder_email,
                'content'=>$request->content,
            ]);
        }
        toastr()->success("تم اضافة تعليقك بنجاح وسيظهر للعامة بعد المراجعة");
        return redirect()->back();
    }

    public function contact_post(Request $request)
    {
        $request->validate([
            'name'=>"required|min:3|max:190",
            'email'=>"nullable|email",
            "phone"=>"required|numeric",
            "message"=>"required|min:3|max:10000",
        ]);
        //if(\MainHelper::recaptcha($request->recaptcha)<0.8)abort(401);
        Contact::create([
            'user_id'=>auth()->check()?auth()->id():NULL,
            'name'=>$request->name,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'message'=>/*"قادم من : ".urldecode(url()->previous())."\n\nالرسالة : ".*/$request->message
        ]);

        toastr()->success('تم استلام رسالتك بنجاح وسنتواصل معك في أقرب وقت');
        return redirect()->back();
    }
    public function category(Request $request,Category $category){
        $articles = Article::where(function($q)use($request,$category){
            if($request->user_id!=null)
                $q->where('user_id',$request->user_id);

            $q->whereHas('categories',function($q)use($request,$category){
                $q->where('category_id',$category->id);
            });
        })->with(['categories','tags'])->withCount(['comments'=>function($q){$q->where('reviewed',1);}])->orderBy('id','DESC')->paginate();
        return view('front.pages.blog',compact('articles','category'));
    }
    public function tag(Request $request,Tag $tag){

        $articles = Article::where(function($q)use($request,$tag){
            if($request->user_id!=null)
                $q->where('user_id',$request->user_id);

            $q->whereHas('tags',function($q)use($request,$tag){
                $q->where('tag_id',$tag->id);
            });
        })->with(['categories','tags'])->withCount(['comments'=>function($q){$q->where('reviewed',1);}])->orderBy('id','DESC')->paginate();

        return view('front.pages.blog',compact('articles','tag'));
    }
    public function article(Request $request,Article $article)
    {
        $article->load(['categories','comments'=>function($q){$q->where('reviewed',1);},'tags'])->loadCount(['comments'=>function($q){$q->where('reviewed',1);}]);
        $this->views_increase_article($article);
        return view('front.pages.article',compact('article'));
    }
    public function page(Request $request,Page $page)
    {

        $customView = 'front.pages.custom-pages.' . $page->slug;

        if(view()->exists($customView)) {
            // If the file exists, return custom page
            return view($customView,compact('page'));
        }

        return view('front.pages.page',compact('page'));
    }
    public function blog(Request $request)
    {
        $articles = Article::where(function($q)use($request){
            if($request->category_id!=null)
                $q->where('category_id',$request->category_id);
            if($request->user_id!=null)
                $q->where('user_id',$request->user_id);
        })->with(['categories','tags'])->withCount(['comments'=>function($q){$q->where('reviewed',1);}])->orderBy('id','DESC')->paginate();
        return view('front.pages.blog',compact('articles'));
    }
    public function views_increase_article(Article $article)
    {
        $data= [
            'ip'=>\UserSystemInfoHelper::get_ip(),
            'prev_link'=>\UserSystemInfoHelper::prev_url(),
            'agent_name'=>request()->header('User-Agent'),
            'browser'=>\UserSystemInfoHelper::get_browsers(),
            'device'=>\UserSystemInfoHelper::get_device(),
            'operating_system'=>\UserSystemInfoHelper::get_os()
        ];
        \App\Jobs\ItemSeenInsertJob::dispatch("\App\Models\Article",$article->id,$data);
    }
    public function builder(Request $request){
        return view('front.pages.builder');
    }

}

