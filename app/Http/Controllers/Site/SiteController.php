<?php

namespace App\Http\Controllers\Site;

use App\Model\Activation;
use App\Model\Answer;
use App\Model\Article;
use App\Model\Attribute;
use App\Model\AttributeGroup;
use App\Model\AttributeValue;
use App\Model\Auction;
use App\Model\AuctionResult;
use App\Model\Brand;
use App\Model\Category;
use App\Model\Consultation;
use App\Model\Contact;
use App\Model\Exam;
use App\Model\favorite;
use App\Model\NewsLatters;
use App\Model\Page;
use App\Model\Product;
use App\Model\Question;
use App\Model\Suggestion;
use App\Model\Systeminf;
use App\Model\Systeminfmanage;
use App\Model\Tag;
use App\Model\Variation;
use App\Model\Video;
use App\Model\Vote;
use App\User;
use App\Utility\Message;
use App\Utility\SendSms;
use App\Utility\Status;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Alert;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use SEOMeta;
use OpenGraph;
use Spatie\PdfToImage\Pdf;
use Twitter;
use Illuminate\Support\Facades\Hash;
use Artesaos\SEOTools\Facades\SEOTools;
## or
use SEO;


class SiteController extends Controller
{
    public static $items = [];
    public static $status = false;

    public function index()
    {
     //   auth()->loginUsingId(1);
    //  dd(Hash::make('Qwerty123456'));   
        // $seo = Systeminf::find(49);
        // createMetaSite($seo);
        $seo=\App\Model\Seo::where([
            ['seoable_id',49],
            ['seoable_type','setting'],
            ])->first();
        SEOMeta::setTitle($seo->title);
        SEOMeta::setDescription($seo->description);
        SEOMeta::addKeyword($seo->keyword);
        OpenGraph::setTitle($seo->title);
        OpenGraph::setDescription($seo->description);
        Twitter::setTitle($seo->title);
        Twitter::setDescription($seo->description);
        
        $sliders = Systeminfmanage::where(['status' => 1, 'systeminf_id' => 4])->latest()->get();
        $boxSlider = Systeminfmanage::where(['status' => 1, 'systeminf_id' => 25])->latest()->get();
        $textAbout = Systeminfmanage::where(['status' => 1, 'systeminf_id' => 34])->latest()->first();
        $course = Systeminf::with(['systeminfmanage' => function ($q) {
            $q->whereStatus(1);
        }])->find(35);
        $articles = Article::whereStatus(1)->latest()->take(5)->get();
        $resultsMembers = Systeminf::with(['systeminfmanage' => function ($q) {
            $q->whereStatus(1);
        }])->find(36);
        $instagram = Systeminfmanage::where(['status' => 1, 'systeminf_id' => 37])->latest()->get();
        $setting_contact = Systeminfmanage::find(17);
        return view('site.index', compact('instagram', 'resultsMembers', 'articles', 'boxSlider', 'sliders', 'textAbout', 'course','setting_contact'));
        
    }

    public function image($product, $image)
    {
        if (Auth::check()) {
            $productUser = Auth::user()->production[0]->pivot->pluck('product_id')->toArray();
            if (in_array($product, $productUser)) {
                $path = public_path() . '/pdf-images/' . $product . '/' . $image;
                return Response::download($path);
            } else {
                return 'Nope, sorry bro, access denied!';
            }
        } else {
            return 'Nope, sorry bro, access denied!';
        }
    }

    public function categorys($slug = null)
    {
        if (!empty($slug)) {
            $category = Category::query()->whereType(Product::class)->whereSlug($slug)->first();
            if (isset($category) && !empty($category)) {
                $categorys = Category::query()->whereType(Product::class)->with(['image'])->
                where('parent_id', $category->id)->orderBy('sorting', 'ASC')->get();
                $products = Product::with(['image'])->whereStatus(1)->take(10)->latest()->get();
                return view('site.categorys', compact('products', 'categorys'));
            } else {
                alert()->error("صفحه مورد نظر یافت نشد", "کاربر گرامی")->showConfirmButton("بستن");
                return Redirect::to('/', 301);
            }
        } else {
            return Redirect::to('/', 301);
        }

    }

    public function sales()
    {
        $products = Product::with(['image'])->whereStatus(1)->whereSales(1)->latest()->paginate(9);
        SEO::setTitle('فروشگاه | حراجی');
        $category = "فروشگاه |  حراجی";
        return view('site.product.product-list', compact('products', 'category'));
    }

    public function changeBrand(Request $request)
    {
        $this->validate($request, [
            'brandId' => 'numeric|required',
        ]);
        $brandId = $request->brandId;
        $brand = Brand::findOrfail($brandId);
        $ids = $brand->id;

        if ($brand->count() > 0) {
            /* check class */
            $class = get_class($brand);
            if (!class_exists($class)) {
                return [
                    'status' => 100,
                    'msg' => 'محصولی یافت نشد!!'
                ];
            }

            if (is_numeric($brandId)) {
                $brands = $class::with(['image'])->whereId($ids)->first();

                if ($brands->count() > 0) {
                    $products = Product::with(['image'])->whereStatus(1)->where('selected_brand', 1)->where('brand_id', $brands->id)->take(5)->get();
                    if (isset($products) && count($products) > 0) {
                        return [
                            'status' => 200,
                            'brand' => $brands,
                            'products' => $products
                        ];
                    } else {
                        return [
                            'status' => 100,
                            'msg' => 'این برند محصولی ندارد'
                        ];
                    }

                } else {
                    return [
                        'status' => 100,
                        'msg' => 'برندی یافت نشد!'
                    ];
                }
            } else {
                return [
                    'status' => 100,
                    'msg' => 'لطفا محصول را به درستی انتخاب کنید'
                ];
            }

        }


    }

    public function poll()
    {
        $poll = Question::with('answers')->where('state', 1)->first();
        return view('site.poll', compact('poll'));
    }

    public function vote(Request $request)
    {

        $this->validate($request, [
            'poll' => "required|numeric",
            'vote' => "required|numeric",
        ]);
        $pollId = intval($request->poll);
        $voteId = $request->vote;
        $ip = $request->ip();

        if (isset($voteId) && !empty($voteId)) {

            if (is_numeric($pollId)) {
                $poll = Question::findOrFail($pollId);

                $answer = Answer::where('question_id', $pollId)->where('id', $voteId)->first();

                if (isset($answer) && $answer->count() > 0) {

                    $votesUser = Vote::where('question_id', $poll->id)->where('ip', $ip)->first();
                    if (!empty($votesUser) && $votesUser->count() > 0) {
                        alert()->error('رای شما قبلا برای این نظرسنجی ثبت شده است.', 'خطا')->showConfirmButton("بستن");
                        return back();
                    }

                    $saveVote = Vote::create([
                        'question_id' => $poll->id,
                        'answer_id' => $voteId,
                        'ip' => $ip
                    ]);

                    if ($saveVote instanceof Vote) {
                        alert()->success("رای شما با موفقیت در سامانه ثبت شد\n با تشکر", "موفقیت آمیز")->showConfirmButton('بستن');
                        return back();
                    } else {
                        alert()->error("لطفا اطلاعات مناسب وارد کنید.", "خطا")->showConfirmButton('بستن');
                        return back();
                    }
                } else {
                    alert()->error('گزینه انتخابی برای این نظرسنجی اشتباه میباشد', 'خطا')->showConfirmButton("بستن");
                    return back();
                }
            }
        } else {
            alert()->error('لطفا گزینه خود را انتخاب نمایید', 'خطا')->showConfirmButton('بستن');
            return back();
        }
    }

    public function page($slug = null)
    {
        if (!empty($slug)) {
            $page = Page::where('slug', $slug)->first();

            if (!isset($page) || empty($page)) {
                alert()->error("صفحه مورد نظر یافت نشد", "کاربر گرامی")->showConfirmButton("بستن");
                return Redirect::to('/', 301);
            }

            $seo = \App\Model\Seo::where([
                ['seoable_id',   $page->id],
                ['seoable_type', 'page'],
            ])->first();

            if ($seo) {
                SEOMeta::setTitle($seo->title);
                SEOMeta::setDescription($seo->description);
                SEOMeta::addKeyword($seo->keyword);
                OpenGraph::setTitle($seo->title);
                OpenGraph::setDescription($seo->description);
                Twitter::setTitle($seo->title);
                Twitter::setDescription($seo->description);
            } else {
                SEOTools::setTitle($page->title);
                SEOTools::setDescription($page->description ?? strip_tags(\Illuminate\Support\Str::limit($page->body, 160)));
            }

            $pageOgImage = isset($page->image[0])
                ? $page->image[0]->url
                : ($page->image_path ? asset('storage/'.ltrim($page->image_path, '/')) : null);
            if ($pageOgImage) {
                OpenGraph::addImage(url($pageOgImage));
            }
            OpenGraph::addProperty('type', 'website');
            SEO::opengraph()->setUrl($page->path());

            $page->increment('viewCount');
            createMetaSite($page);

            $similarArticles = Article::query()
                ->whereHas('categories', function ($q) {
                    $q->where('slug', 'دفاع-شخصی');
                })
                ->whereStatus(1)
                ->take(6)
                ->latest()
                ->get();

            if ($similarArticles->count() < 3) {
                $similarArticles = Article::whereStatus(1)->take(6)->latest()->get();
            }

            $lastArticles = Article::whereStatus(1)->take(10)->latest()->get();

            return view('site.page', compact('page', 'similarArticles', 'lastArticles'));

        } else {
            return Redirect::to('/', 301);
        }
    }

    public function sellerProfile($id = null)
    {
        if (!empty($id)) {
            $user = User::where('id', $id)->first();
            if (isset($user) && !empty($user)) {
                $products = Product::with(['image', 'variations' => function ($query) use ($user) {
                    $query->where('user_id', $user);
                }])->latest()->paginate(12);
                $comments = $user->comments()->where('status', 1)->where('parent_id', 0)->latest()->paginate(3);
                $categorys = Category::query()->whereType(Product::class)->with(['products.variations'])->orderBy('sorting', 'ASC')->get();
                SEO::setTitle($user->id + 102);
                SEO::opengraph()->setUrl(Url(''));
                return view('site.profile', compact('user', 'products', 'comments', 'categorys'));
            } else {
                alert()->error("صفحه مورد نظر یافت نشد", "کاربر گرامی")->showConfirmButton("بستن");
                return Redirect::to('/', 301);
            }
        } else {
            return Redirect::to('/', 301);
        }
    }

    public function article($slug = null)
    {
        if (!empty($slug)) {
            $article = Article::with('image')->where('slug', $slug)->where('status', 1)->first();

            if (!$article) {
                abort(404);
            }

            $seo=\App\Model\Seo::where([
            ['seoable_id',$article->id],
            ['seoable_type','article'],
            ])->first();
            if($seo){
                SEOMeta::setTitle($seo->title);
                SEOMeta::setDescription($seo->description);
                SEOMeta::addKeyword($seo->keyword);
                OpenGraph::setTitle($seo->title);
                OpenGraph::setDescription($seo->description);
                Twitter::setTitle($seo->title);
                Twitter::setDescription($seo->description);
            }else{
                SEOTools::setTitle($article->title);
                SEOTools::setDescription($article->description);
                OpenGraph::setTitle($article->title);
                OpenGraph::setDescription($article->description);
            }
       
            if ($article) {
               
                $comments = $article->comments()->where('status', 1)->where('parent_id', 0)->latest()->paginate(5);
                $article->increment('viewCount');
                $categorys = Category::query()->whereType(Article::class)->whereStatus(1)
                    ->orderBy('sorting', 'ASC')
                    ->get();
                    
                createMetaSite($article);
                SEO::opengraph()->setUrl(route('site.article', $article->slug));
                // مقاله‌های همگرا (ایمپورت/فرم) تصویر را در ستونِ image_path دارند، نه رابطه‌ی
                // قدیمیِ image؛ همان الگوی گاردِ خودِ ویو + fallback به image_path تا 500 ندهد.
                $ogImageUrl = isset($article->image[0])
                    ? $article->image[0]->url
                    : ($article->image_path ? asset('storage/'.ltrim($article->image_path, '/')) : null);
                if ($ogImageUrl) {
                    OpenGraph::addImage(Url($ogImageUrl));
                }
                SEO::opengraph()->addProperty('type', 'article');
               
                $similarArticles = Article::query()->whereHas('categories', function ($q) use ($article) {
                    if($article->categories->count())
                    $q->where('category_id', $article->categories[0]->id);
                })->whereStatus(1)->take(6)->get();
         
                $lastArticles = Article::whereStatus(1)->take(10)->latest()->get();



			// 	 if(isset($_GET['a'])){
			// 	 dd($lastArticles);
			//  }


                return view('site.article', compact('article', 'categorys',
                    'similarArticles', 'lastArticles', 'comments'));
            } else {
                alert()->error("لطفا اطلاعات مناسب را وارد نمایید\n با تشکر", "خطا")->showConfirmButton("بستن");
                return back();
            }
        } else {
            $articles = Article::with('image')->where('status', 1)->latest()->paginate(8);
            $categorys = Category::whereType(Article::class)->whereStatus(1)->orderBy('sorting', 'ASC')->get();
            $articleViewCount = Article::where('status', 1)->orderBy('viewCount', 'desc')->take(7)->get();
            $seo = Systeminf::find(51);
            createMetaSite($seo);
            return view('site.articles', compact('articles', 'categorys', 'articleViewCount'));
        }
    }

    public function ArticleTags($slug)
    {
        if (isset($slug) && !empty($slug)) {
            $tag = Tag::whereSlug($slug)->whereStatus(1)->firstOrFail();
            SEO::setTitle($tag->title);
            $articles = $tag->article;
            $categorys = Category::query()->whereType(Article::class)->whereStatus(1)->orderBy('sorting', 'ASC')->get();
            $articleViewCount = Article::where('status', 1)->orderBy('viewCount', 'desc')->take(7)->get();
            return view('site.articles', compact('articles', 'categorys', 'articleViewCount'));

        } else {
            return Redirect::route('site.index');
        }
    }

    public function ProductTags(Request $request, $slug)
    {
        if (isset($slug) && !empty($slug)) {
            $tag = Tag::whereSlug($slug)->whereStatus(1)->firstOrFail();
            SEO::setTitle($tag->title);
//            $products = $tag->product;
            $products = $this->CustomPaginate($tag->product, $request, 12);


            $similarProducts = Product::where('status', 1)->limit(10)->orderBy('viewCount', 'desc')->get();
            $category = false;
            return view('site.product.product-list', compact('products', 'similarProducts', 'category'));

        } else {
            return Redirect::route('site.index');
        }
    }

    public function categoryArticle($slug = null)
    {
        if (!empty($slug)) {
            $findCategory = Category::query()->whereType(Article::class)->whereSlug($slug)->firstOrFail();
            $articles = Article::query()->whereHas('categories', function ($q) use ($findCategory) {
                $q->where('category_id', $findCategory->id);
            })
                ->where('status', 1)
                ->latest()
                ->paginate(8);
            $categorys = Category::query()->whereType(Article::class)->whereStatus(1)->orderBy('sorting', 'ASC')->get();
            $articleViewCount = Article::where('status', 1)->orderBy('viewCount', 'desc')->take(10)->get();
            $logo = Systeminfmanage::where(['status' => 1, 'id' => 1])->latest()->first();
            $title = $findCategory->title;
            SEO::setTitle($title);
            SEO::opengraph()->setUrl(Url(''));
            return view('site.articles', compact('articles', 'categorys', 'articleViewCount', 'logo', 'title'));
        }
        return back();
    }

    public function exam()
    {
        $examData = Systeminfmanage::where('status', 1)->where('systeminf_id', 17)->get();
        $seo = Systeminf::find(53);
        createMetaSite($seo);
        return view('site.exam', compact('examData'));
    }

    public function examStore(Request $request)
    {
        if (Auth::check()){
            $this->validate($request, [
                'name' => "required",
                'mobile' => "required",
                'video' => 'nullable|max:200000',
            ]);

            $exams = Exam::query()->create([
                'name' => $request->name,
                'mobile' => $request->mobile,
                'description' => $request->description,
            ]);
            $files = $request->file('video');

            if ($exams instanceof Exam) {

                if (isset($files) && !empty($files)) {
                    foreach ($files as $file) {
                        $filename = $file->getClientOriginalName();
                        $file->storeAs('exams', $filename, 'public');
                        Video::create([
                            'url' => $filename,
                            'videoable_type' => get_class($exams),
                            'videoable_id' => $exams->id,
                            'user_id' => Auth::id(),
                        ]);
                    }
                }


                alert()->success('موفقیت آمیز !', "با موفقیت ارسال گردید")->showConfirmButton('بستن');
                return redirect()->route('site.index');
            } else {
                alert()->success('متاسفیم', Message::ErrorMessageContact)->showConfirmButton('بستن');
                return redirect()->route('site.index');
            }
        }else{
            alert()->success('متاسفیم',"ابتدا وارد شوید")->showConfirmButton('بستن');
            return redirect()->route('login');
        }

    }

    public function aboutUs()
    {
        $about = Systeminfmanage::where('status', 1)->where('systeminf_id', 17)->get();

        $music = Systeminfmanage::where('status', 1)->where('systeminf_id', 47)->first();

        SEO::setTitle('درباره ما');
        return view('site.about', compact('about', 'music'));
    }

    public function question()
    {
        $faq = Systeminfmanage::where(['status' => 1, 'systeminf_id' => 8])->latest()->get();
        return view('site.frequently-asked-questions', compact('faq'));
    }

    public function suggestion()
    {
        return view('site.suggestion');
    }

    public function contactUs()
    {
        $map = Systeminfmanage::where('status', 1)->where('systeminf_id', 16)->first();
        $contactUs = Systeminfmanage::where(['status' => 1, 'systeminf_id' => 7])->latest()->first();
        $seo = Systeminf::find(7);
        createMetaSite($seo);
        return view('site.contact', compact('map', 'contactUs'));
    }

    public function saveConsultation(Request $request)
    {
        $this->validate($request, [
            'name' => "required",
            'mobile' => "required",
        ]);

        $saveData = [
            'name' => $request->input('name'),
            'birth_date' => $request->input('birth_date'),
            'height' => $request->input('height'),
            'weight' => $request->input('weight'),
            'address' => $request->input('address'),
            'mobile' => $request->input('mobile'),
            'job' => $request->input('job'),
            'history_sports_activities' => $request->input('history_sports_activities'),
            'prohibition_sports' => $request->input('prohibition_sports'),
            'physical_limitations' => $request->input('physical_limitations'),
            'fear_injury' => $request->input('fear_injury'),
            'self_defense_skills' => $request->input('self_defense_skills'),
            'purpose_exercise' => $request->input('purpose_exercise'),
            'get_acquainted' => $request->input('get_acquainted'),
            'social_networkId' => $request->input('social_networkId'),
            'status' => Status::deActive,
        ];

        $create = Consultation::create($saveData);
        if ($create instanceof Consultation) {
            alert()->html('موفقیت آمیز !', "ممنون از همراهیتون <br> با شما برای مشاوره تماس خواهیم گرفت", 'success')->showConfirmButton('بستن');
            return redirect()->route('site.index');
        } else {
            alert()->success('متاسفیم', Message::ErrorMessageContact)->showConfirmButton('بستن');
            return redirect()->route('site.index');
        }
    }

    public function Consultation()
    {
        $seo = Systeminf::find(52);
        createMetaSite($seo);
        return view('site.consultation');
    }

    public function saveContactUs(Request $request)
    {
    
        // dd($request['g-recaptcha-response']);
     

        if (auth()->check()) {
            $this->validate($request, [
                'body' => "required|min:5",
                'email' => "required|email",
                 'g-recaptcha-response' => 'required|captcha'
            ]);

            $saveData = [
                'user_id' => Auth::id(),
                'name' => auth()->user()->name . " " . auth()->user()->family,
                'email' => $request->input('email'),
                'body' => $request->input('body'),
                'ip' => $request->ip(),
                'status' => 0
            ];

            $create = Contact::create($saveData);
            if ($create instanceof Contact) {
                Auth::user()->update(['email' => $request->input('email')]);
                alert()->success('موفقیت آمیز !', Message::SuccessMessageContact)->showConfirmButton('بستن');
                return redirect()->route('site.index');
            } else {
                alert()->success('متاسفیم', Message::ErrorMessageContact)->showConfirmButton('بستن');
                return redirect()->route('site.index');
            }

        } else {

            $this->validate($request, [
                'name' => "required",
                'email' => "required",
                'body' => "required",
                  'g-recaptcha-response' => 'required|captcha'
            ]);

            $saveData = [
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'body' => $request->input('body'),
                'ip' => $request->ip()
            ];

            $create = Contact::create($saveData);
            if ($create instanceof Contact) {
                alert()->success('موفقیت آمیز !', Message::SuccessMessageContact)->showConfirmButton('بستن');
                return redirect()->route('site.index');
            } else {
                alert()->success('متاسفیم', Message::ErrorMessageContact)->showConfirmButton('بستن');
                return redirect()->route('site.index');
            }

        }
    }

    public function saveNewsLatter(Request $request)
    {
        $email = $request->input('email');

        $saveData = [
            'email' => $email,
            'name' => $request->input('name'),
            'mobile' => $request->input('mobile'),
        ];
        if (isset($email) && $email != "" && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $checkEmail = NewsLatters::where('email', $email)->first();
            if (isset($checkEmail) && !empty($checkEmail)) {
                alert()->error("ایمیل شما قبلا در سامانه ثبت شده است \n با تشکر", 'خطا')->showConfirmButton('بستن');
                return back();
            } else {
                $create = NewsLatters::create($saveData);
                if ($create instanceof NewsLatters) {
                    alert()->success(" ایمیل شما در سامانه ثبت شد\n با تشکر", 'موفقیت آمیز !')->showConfirmButton('بستن');
                    return back();
                } else {
                    alert()->error("لطفا اطلاعات مناسب وارد نمایید", 'خطا')->showConfirmButton('بستن');
                    return back();
                }
            }
        } else {
            alert()->error("لطفا ایمیل خود را وارد نمایید", 'ناموفق')->showConfirmButton('بستن');
            return back();
        }

    }

    /*Search in Product (header website)*/
    public function SearchHeader(Request $request)
    {
        $title = $request->title;

        $products = Product::with(['variations', 'image'])->latest()->when($title, function ($query) use ($title) {
            $query->whereStatus(1)->where('title', 'like', '%' . $title . '%');
        })->whereStatus(1)->paginate(9);
        $category = false;
        SEO::setTitle('جستجو');
        SEOMeta::addMeta('robots', 'noindex,follow');
        return view('site.product.product-list', compact('products', 'title', 'category'));

    }

    /** Single Search Product**/
    public function SingleSearchProduct(Request $request)
    {
        $title = $request->title;

        $products = Product::with(['variations'])->where('title', 'like', '%' . $title . '%')->latest()->whereStatus(1)->paginate(9);
        SEO::setTitle($title);
        return view('site.search', compact('products'));
    }

    /** list Product base on Category*/
    public function CategoryList(Request $request, $slug = null)
    {
        if (!empty($slug)) {
            $category = Category::with(['attributes' => function ($query) {
                $query->where('is_filter', 1);
            }])->where('slug', $slug)->firstOrfail();
            createMetaSite($category);
            if (isset($category->categories) && !empty($category->categories) && count($category->categories) > 0) {
                $products = Product::query()->
                whereHas('categories', function ($q) use ($category) {
                    $q->whereIn('category_id', $category->categories->pluck('id')->toArray());
                })->
                whereStatus(1)->
                orderBy('sorting', 'desc')->whereStatus(1)->paginate(12);
                SEO::setTitle($category->title);
                // $category = false;
               
                return view('site.product.product-list', compact('products', 'category'));

            } else {
                $attributes = self::checkAttrAndAttrGroupForCategoryProduct($category);
                $products = Product::with('image', 'variations')->whereStatus(1)->
                whereHas('categories', function ($q) use ($category) {
                    $q->where('category_id', $category->id);
                })->
                orderBy('sorting', 'desc')->paginate(12);
                $maxPrice = Variation::whereIn('product_id', $products->pluck('id')->toArray())->max('price');
                SEO::setTitle($category->title);
                return view('site.product.product-list', compact('products', 'category', 'attributes', 'maxPrice'));

            }


        } else {
            return Redirect::to('/', 301);
        }

    }
    /** list Product base on Category*/


    /** Start Function Filter Base On Attr and Attr Value**/
    public function FilterBaseOnAttr(Request $request)
    {
        $brand = $request->input('brand');
        $category = $request->input('category');
        $attribute = $request->input('attr');
        $min = $request->input('min');
        $max = $request->input('max');
        $products = [];
        $selected = [];
        $productByBrandOrCategory = '';
        $loadMore = $request->input('load');
        $lastID = $request->input('lastID');
        $countProduct = $request->input('countProduct');

        // Start Of validation Brand and category
        if (isset($brand) && !empty($brand)) {
            $findBrand = Brand::whereStatus(1)->findOrfail($brand);
            array_push($selected, $findBrand->title);
        }
        if (isset($category) && !empty($category)) {
            $findCategoryProduct = Category::query()->whereType(Product::class)->whereStatus(1)->findOrfail($category);
            array_push($selected, $findCategoryProduct->title);
        }
        // End Of validation Brand and category

        if (isset($brand) || isset($category) && !empty($brand) || !empty($category)) {
            $productByBrandOrCategory = Product::with('variations')->
            whereStatus(1)->orderBy('id', 'desc')->
            when($brand, function ($query) use ($brand) {
                $query->where('brand_id', $brand);
            })->
            when($category, function ($query) use ($category) {
                $query->whereHas('categories', function ($q) use ($category) {
                    $q->where('category_id', $category);
                });
            })->
            when($min, function ($query) use ($min, $max) {
                $query->whereHas('variations', function ($qVariation) use ($min, $max) {
                    $qVariation->whereBetween('price', [$min, $max]);
                });
            })->
            get();
        }


        foreach ($attribute as $itemValue) {
            if (isset($itemValue) && !empty($itemValue)) {
                $FindAttrValue = AttributeValue::find($itemValue);
                $attrValues = AttributeValue::where('value', $FindAttrValue->value)->get();
                foreach ($attrValues as $itemAttrValue) {
                    array_push($selected, $itemAttrValue->value);
                    if (isset($itemAttrValue->products[0]) && !empty($itemAttrValue->products[0])) {
                        array_push($products, $itemAttrValue->products[0]);
                    }
                }

            }
        }

//        if (isset($productByBrandOrCategory) && !empty($productByBrandOrCategory)) {
//            foreach ($productByBrandOrCategory as $itemProduct) {
//                array_push($products, $itemProduct);
//            }
//        }


        $selected = array_unique($selected);
        $products = collect($products)->sortBy('id')->unique('id');

        if ($products->count() == $countProduct) {
            return [
                'status' => 100,
                'message' => "چیزی برای نمایش بیشتر یافت نشد"
            ];
        }

        if (isset($loadMore) && !empty($lastID)) {

            $productNew = $products->where('id', ">", $lastID);
            $productsLast = $products->where('id', "<=", $lastID);
            $products = $productsLast->merge($productNew);
        }

        if (empty($countProduct)) {
            $countProduct = 0;
        }
        $products = self::CustomPaginate($products, $request, 9 + $countProduct);
        $countProduct = $products->count();

        $category = false;

        $view = view('site.product.partials.result-filter', compact('products', 'countProduct', 'lastID'))->render();
        $viewFilter = view('site.product.partials.result-selected', compact('selected'))->render();
        return response()->json([
            'html' => $view,
            'selected' => $viewFilter,
        ]);
    }
    /** End Function Filter Base On Attr and Attr Value**/

    /** list Product base on Brand*/
    public function BrandList($slug = null)
    {
        if (!empty($slug)) {
            $brand = Brand::with('image')->where('slug', $slug)->firstOrfail();
            $products = Product::with('image', 'variations')->whereStatus(1)->where('brand_id', $brand->id)->paginate(12);
            SEO::setTitle($brand->title);
            return view('site.brand', compact('products', 'brand'));
        } else {
            return Redirect::to('/', 301);
        }

    }
    /** list Product base on Brand*/

    /*Start Of Save Comment */
    public function SaveComment(Request $request, $id)
    {
        $user_id = Auth::user()->id;

        $this->validate($request, [
            'comment' => 'required|min:5',
            'commentable_id' => 'numeric|required',
            'commentable_type' => 'required',
        ]);

        $parent_id = $request->input('parent_id');
        $id = $request->input('commentable_id');
        $class = $request->input('commentable_type');

        /* check class */
        if (!class_exists($class)) {
            alert()->error("ارسال پیغام شما با شکست روبرو شد , لطفا دوباره امتحان نمایید.\n با تشکر", "خطا")->showConfirmButton("بستن");
            return back();
        }

        if (is_numeric($id)) {
            $comment = $class::whereId($id)->first();
            if ($comment->count() > 0) {

                $comment->comments()->create([
                    'user_id' => $user_id,
                    'status' => 0,
                    'comment' => $request->input('comment'),
                    'commentable_id' => $comment->id,
                    'commentable_type ' => get_class($comment)
                ]);

                alert()->success("دیدگاه شما ثبت شد\n با تشکر", "موفقیت آمیز")->showConfirmButton("بستن");
                return redirect()->back();

            } else {
                alert()->error("لطفا اطلاعات مناسب را وارد نمایید\n با تشکر", "خطا")->showConfirmButton("بستن");
                return redirect()->back();
            }
        } else {
            return redirect()->back()->with(['error' => Message::illegalError]);
        }
    }
    /*End Of Save Comment*/


    /* start activation user */
    public function activation($token)
    {

        $activationCode = Activation::whereCode($token)->first();

        /* exist or not */
        if (!$activationCode || $activationCode->expire < Carbon::now()) {
            alert()->error('خطا', 'زمان شما به پایان رسیده است , لطفا چند لحظه دیگر امتحان فرمایید')->showConfirmButton('بستن');
            return redirect('/');
        }


        /* used */
        if ($activationCode->used == true) {
            alert()->error('خطا', 'زمان شما به پایان رسیده است , لطفا چند لحظه دیگر امتحان فرمایید')->showConfirmButton('بستن');
            return redirect('/');
        }

        $activationCode->update([
            'used' => true,
        ]);

        $activationCode->user()->update([
            'active' => 1
        ]);

        auth()->loginUsingId($activationCode->user->id);
        alert()->success('موفقیت آمیز', "شما به درستی وارد سایت شدید.\n با تشکر")->showConfirmButton('بستن');
        return redirect('/');
    }
    /* end activation user */

    /* Start Of AddFavorites*/
    public function AddFavorites(Request $request)
    {
        $this->validate($request, [
            'id' => 'numeric|required',
        ]);
        $id = $request->id;
        $product = Product::findOrfail($id);
        $ids = $product->id;


        if (\auth()->check()) {
            $user_id = Auth::user()->id;
            if ($product->count() > 0) {
                /* check class */
                $class = get_class($product);
                if (!class_exists($class)) {
                    return [
                        'status' => 100,
                        'msg' => 'محصولی یافت نشد!!'
                    ];
                }

                if (is_numeric($id)) {
                    $productfavorite = $class::whereId($id)->first();
                    if ($productfavorite->count() > 0) {
                        $findFavorite = favorite::where([
                            ['user_id', '=', $user_id],
                            ['favoriteable_id', '=', $ids],
                            ['favoriteable_type', '=', 'product']
                        ])->count();
                        if ($findFavorite > 0) {
                            return [
                                'status' => 101,
                                'msg' => 'محصول انتخابی در لیست علاقه مندی های شما وجود دارد'
                            ];
                        } else {
                            $productfavorite->favorites()->create([
                                'user_id' => $user_id,
                                'favoriteable_id' => $productfavorite->id,
                                'favoriteable_type ' => get_class($productfavorite)
                            ]);
                            return [
                                'status' => 200,
                                'msg' => 'محصول مورد نظر به علاقه مندی شما اضافه شد'
                            ];
                        }


                    } else {
                        return [
                            'status' => 100,
                            'msg' => 'محصولی یافت نشد!'
                        ];
                    }
                } else {
                    return [
                        'status' => 100,
                        'msg' => 'لطفا محصول را به درستی انتخاب کنید'
                    ];
                }

            }
        } else {
            return [
                'status' => 100,
                'msg' => 'برای افزودن به علاقه مندی ها ابتدا وارد شوید'
            ];
        }
    }


    public function buyAuction($slug)
    {
        if (!empty($slug)) {
            $product = Product::with(['auction'])->whereSlug($slug)->firstOrFail();
            if ($product->auction->status == 1) {
                if (isset($product->auction->users[0]) && !empty($product->auction->users[0])) {
                    $usersAuction = $product->auction->users[0]->pivot->pluck('user_id')->toArray();
                    if (in_array(Auth::user()->id, $usersAuction)) {
                        alert()->warning('شما قبلا در این مزایده شرکت کردیده اید', 'دقت کنید')->showConfirmButton('بستن');
                        return back();
                    }
                }
                if (count($product->auction->users) < $product->auction->participant_count) {
                    $paymentAmount = $product->auction->click_count * $product->auction->every_click_price_for_pay + $product->auction->start_price;
                    // TODO Payment ...
                    $payment = "success";
                    if ($payment == "success") {
                        // TODO create in table payment And Update Payment Status ...
                        // TODO create in table Auction_entry ...
                        $product->auction->users()->attach([Auth::user()->id]);
                        alert()->success('پرداخت شما با موفقیت انجام شد', 'موفق')->showConfirmButton('بستن');
                        return back();
                    } else {
                        alert()->error('پرداخت شما با موفقیت انجام نشد', 'متاسفیم')->showConfirmButton('بستن');
                        return back();
                    }
                } else {
                    alert()->warning('ظرفیت این مزایده به اتمام رسیده است', 'متاسفیم')->showConfirmButton('بستن');
                    return back();
                }
            } else {
                alert()->warning('زمان این مزایده به اتمام رسیده است', 'متاسفیم')->showConfirmButton('بستن');
                return back();
            }


        } else {
            return back();
        }
    }

    public function storeSuggestion(Request $request)
    {
        $auctionId = $request->input('auctionId');
        if (isset($auctionId) && !empty($auctionId) && is_numeric($auctionId)) {
            if (Auth::check()) {
                $auction = Auction::findOrFail($auctionId);
                if ($auction->status == 1) {
                    if ($this->CapacityAuction($auction) == true) {
                        if ($auction->start_date < Carbon::now()->timestamp) {
                            if (count(Auth::user()->suggestion->where('auction_id', $auction->id)) < $auction->click_count) {
                                $firstSuggestion = $auction->suggestion()->latest()->first();
                                $nowAuctionPrice = isset($firstSuggestion) && !empty($firstSuggestion) ? $firstSuggestion->amount + $auction->every_click_price : $auction->start_price + $auction->every_click_price;
                                if ($nowAuctionPrice <= $auction->end_price) {
                                    $suggestion = Suggestion::create([
                                        'user_id' => Auth::user()->id,
                                        'auction_id' => $auction->id,
                                        'amount' => $nowAuctionPrice,
                                        'click_the_rest' => count(Auth::user()->suggestion->where('auction_id', $auction->id)) == 0 ? $auction->click_count - 1 : Auth::user()->suggestion->where('auction_id', $auction->id)->sortBy('click_the_rest')->first()->click_the_rest - 1,
                                    ]);
                                    $latestSuggestion = Suggestion::where('auction_id', 1)->orderBy('amount', 'desc')->first();
                                    $latestSuggestion = isset($latestSuggestion) && !empty($latestSuggestion) ? Carbon::parse($latestSuggestion->created_at)->addSeconds(12)->timestamp : Carbon::now()->addSeconds(12)->timestamp;
                                    $latestSuggestion = \Hekmatinasser\Verta\Verta::createTimestamp((int)$latestSuggestion)->formatGregorian('M d, Y H:i:s');
                                    return [
                                        'status' => 200,
                                        'message' => "پیشنهاد شما ثبت گردید",
                                        'latestSuggestion' => $latestSuggestion
                                    ];
                                } else {
                                    return [
                                        'status' => 200,
                                        'message' => "قیمت پیشنهادی بالا تر از سقف مجاز میباشد!"
                                    ];
                                }
                            } else {
                                return [
                                    'status' => 100,
                                    'message' => "تعداد کلیک شما به اتمام رسید"
                                ];
                            }
                        } else {
                            return [
                                'status' => 100,
                                'message' => "زمان برگزاری مزایده نرسیده است"
                            ];
                        }
                    } else {
                        return [
                            'status' => 100,
                            'message' => "ظرفیت این مزایده تکمیل نشده ، لطفا بعد از تکمیل ظرفیت مراجعه فرمایید"
                        ];
                    }
                } else {
                    return [
                        'status' => 100,
                        'message' => "این مزایده به اتمام رسیده"
                    ];
                }


            } else {
                return [
                    'status' => 403,
                    'message' => "برای ثبت پیشنهاد ابتدا وارد شوید"
                ];
            }
        } else {
            return [
                'status' => 100,
                'message' => 'متاسفیم بعدا تلاش کنید',
            ];
        }
    }

    public function updateSuggestion(Request $request)
    {
        $auctionId = $request->input('auctionId');
        $auction = Auction::with(['suggestion'])->findOrFail($auctionId);
        $view = view('site.product.partials.latest-suggestion', ['suggestions' => $auction->suggestion])->render();
        if (isset($auction->suggestion) && !empty($auction->suggestion) && count($auction->suggestion) > 0) {
            $latestSuggestion = $auction->suggestion->sortByDesc('amount')->first();
            $latestSuggestion = Carbon::parse($latestSuggestion->created_at)->addSeconds(12)->timestamp;
            $latestSuggestion = \Hekmatinasser\Verta\Verta::createTimestamp((int)$latestSuggestion)->formatGregorian('M d, Y H:i:s');
        }


        return response()->json([
            'html' => $view,
            'latestSuggestion' => isset($latestSuggestion) ? $latestSuggestion : null,
            'status' => $auction->status,
        ]);
    }

    public function winnerAuction(Request $request)
    {
        $auction = Auction::find($request->input('auctionId'));
        $auction->update([
            'status' => 0
        ]);
        $winner = $auction->suggestion->sortByDesc('amount')->first();
        $losersWithOneWinner = $auction->users->pluck('id')->toArray();
        foreach ($losersWithOneWinner as $key => $item) {
            AuctionResult::create([
                'user_id' => $item,
                'auction_id' => $auction->id,
                'type' => $winner->user_id == $item ? 1 : 0
            ]);

            /* Return the auction entry amount(START_RPICE) to the wallet of losers */
            if ($item != $winner->user_id) {
                $auction->users[$key]->update([
                    'wallet' => $auction->users[$key]->wallet + $auction->start_price
                ]);

            }

            // TODO Return Click Price With Create Coupon For Any User (Expire 1 Month)

        }

        return [
            'status' => 200,
            'message' => "success..."
        ];
    }


    /* end Of AddFavorites*/

    public function searchLive(Request $request)
    {
        $word = $request->input('search');
        $findBrand = Brand::where('title', 'like', '%' . $word . '%')->where('status', 1)->latest()->get();
        $view = view('site.liveSearch.search', compact('findBrand'))->render();
        return response()->json(['html' => $view]);
    }

    /** Extra Function Site Controller **/

    public static function checkAttrAndAttrGroupForCategoryProduct(Category $categoryProduct)
    {
        $id = $categoryProduct->id;
        if (!empty($categoryProduct->attributes) && count($categoryProduct->attributes)) {
            $attributeGroups = Attribute::whereHas('attributevalue', function ($adCatQuery) use ($id) {
                $adCatQuery->where('category_id', $id);
            })->with(['attributevalue' => function ($attVal) use ($id) {
                $attVal->where('category_id', $id);
            }])->whereHas('categoryProducts', function ($adCatQuery) use ($id) {
                $adCatQuery->where('category_id', $id)->where('is_filterable', 1);
            })->whereStatus(1)->distinct()->get();

            $attributeGroups->map(function ($attributeGroup) {
                array_unshift(self::$items, $attributeGroup);
            });
            self::$status = true;
        } else
            self::$status = false;
        return self::$items;
    }

    public static function CustomPaginate($array, $request, $perPage)
    {
        // Get current page form url e.x. &page=1
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        // Create a new Laravel collection from the array data
        $itemCollection = collect($array);


        // Slice the collection to get the items to display in current page
        $currentPageItems = $itemCollection->slice(($currentPage * $perPage) - $perPage, $perPage)->all();

        // Create our paginator and pass it to the view
        $paginatedItems = new LengthAwarePaginator($currentPageItems, count($itemCollection), $perPage);

        // set url path for generated links
        $paginatedItems->setPath($request->url());

        return $paginatedItems;
    }

    public function CapacityAuction($auction)
    {
        if ($auction->participant_count == count($auction->users)) {
            return true;
        }
        return false;
    }


}