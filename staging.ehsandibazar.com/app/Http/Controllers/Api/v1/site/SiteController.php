<?php

namespace App\Http\Controllers\Api\v1\site;

use App\Model\Activation;
use App\Model\Article;
use App\Model\Attribute;
use App\Model\AttributeType;
use App\Model\AttributeValue;
use App\Model\Brand;
use App\Model\Category;
use App\Model\Contact;
use App\Model\favorite;
use App\Model\NewsLatters;
use App\Model\Page;
use App\Model\Product;
use App\Model\Systeminfmanage;
use App\Utility\Message;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use SEO;
use Illuminate\Support\Facades\Validator;

class SiteController extends Controller
{

    public static $items = [];
    public static $status = false;

    public function index()
    {
        $sliders = Systeminfmanage::whereStatus(1)->where('systeminf_id', 22)->get();
        $ads = Systeminfmanage::whereStatus(1)->where('systeminf_id', 21)->first();
        $categories = Category::whereType(Product::class)->whereStatus(1)->where('parent_id', 0)->take(5)->get();
        $lastProducts = Product::with(['image'])->whereStatus(1)->take(8)->latest()->get();
        $topRatedProducts = Product::with(['image'])->whereStatus(1)->take(8)->latest()->get();
        $specialProducts = Product::with(['image'])->whereStatus(1)->whereSpecial(1)->take(8)->latest()->get();
        $viewCountProducts = Product::with(['image'])->whereStatus(1)->take(8)->orderBy('viewCount', 'desc')->get();
        $amazingProducts = Product::with(['image'])->whereStatus(1)->whereAmazing(1)->take(8)->latest()->get();
        $articles = Article::whereStatus(1)->take(3)->latest()->get();
        $topRatedProductsView = view('app.site.index', ['products' => $topRatedProducts])->render();

        return response([
            'status' => 200,
            'data' => [
                'sliders' => $sliders,
                'ads' => $ads,
                'categories' => $categories,
                'lastProducts' => $lastProducts,
                'topRatedProducts' => $topRatedProductsView,
                'specialProducts' => $specialProducts,
                'viewCountProducts' => $viewCountProducts,
                'amazingProducts' => $amazingProducts,
                'articles' => $articles,
            ],
            'message' => 'success',
        ]);

    }

    public function Currency()
    {
        SEO::setTitle('currency');
        $exchangeRate = Systeminfmanage::where(['status' => 1, 'id' => 67])->first();
        $currencyChart = Systeminfmanage::where(['status' => 1, 'systeminf_id' => 25])->latest()->get();
        return response([
            'status' => 200,
            'data' => [
                'exchangeRate' => $exchangeRate,
                'currencyChart' => $currencyChart,
            ],
            'message' => 'success',
        ]);
    }


    public function page($slug = null)
    {
        if (!empty($slug)) {
            $page = Page::where('slug', $slug)->first();
            if (isset($page) && !empty($page)) {
                $page->increment('viewCount');
                SEO::setTitle($page->title);
                SEO::setDescription(strip_tags(str_limit($page->body, 230)));
                SEO::opengraph()->setUrl(Url(''));

                return response([
                    'status' => 200,
                    'data' => [
                        'page' => $page,
                    ],
                    'message' => 'success',
                ]);

            } else {
                alert()->error("The requested page could not be found", "Dear user")->showConfirmButton("close");
                return Redirect::to('/', 301);
            }
        } else {
            return Redirect::to('/', 301);
        }
    }


    public function article($slug = null)
    {
        if (!empty($slug)) {
            $article = Article::with(['category', 'image', 'user'])->where('slug', $slug)->where('status', 1)->first();
            if ($article->count() > 0) {
                $article->increment('viewCount');
                $categorys = Categoryarticle::whereStatus(1)->latest()->get();


                return response([
                    'status' => 200,
                    'data' => [
                        'article' => $article,
                        'categorys' => $categorys,
                    ],
                    'message' => 'success',
                ]);
            } else {
                alert()->error("Please enter the appropriate information \n Thanks", "error")->showConfirmButton("close");
                return back();
            }
        } else {
            $articles = Article::with(['category', 'image', 'user'])->where('status', 1)->paginate(2);
            $categorys = Categoryarticle::whereStatus(1)->latest()->get();

            return response([
                'status' => 200,
                'data' => [
                    'articles' => $articles,
                    'categorys' => $categorys,
                ],
                'message' => 'success',
            ]);
        }
    }

    public function categoryArticle($slug)
    {
        $findCategory = Categoryarticle::whereSlug($slug)->firstOrFail();
        $articles = Article::with(['image', 'user', 'category'])->whereCat_id($findCategory->id)->latest()->get();

        return response([
            'status' => 200,
            'data' => [
                'articles' => $articles,
            ],
            'message' => 'success',
        ]);
    }

    public function aboutUs()
    {
        $about = Systeminfmanage::where('status', 1)->where('systeminf_id', 7)->first();
        SEO::setTitle('about us');

        return response([
            'status' => 200,
            'data' => [
                'about' => $about,
            ],
            'message' => 'success',
        ]);
    }

    public function contactUs()
    {
        $map = Systeminfmanage::where('status', 1)->where('systeminf_id', 5)->first();
        $contactMeData = Systeminfmanage::where('status', 1)->where('systeminf_id', 9)->first();
        SEO::setTitle('تماس باما');

        return response([
            'status' => 200,
            'data' => [
                'map' => $map,
                'contactMeData' => $contactMeData,
            ],
            'message' => 'success',
        ]);
    }


    public function saveContactUs(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => "required",
            'email' => "required",
            'body' => "required",
        ]);

        if ($validator->fails()) {
            return response([
                'status' => 300,
                'error' => $validator->errors()->all(),
                'message' => 'validation error',
            ]);
        }

        $saveData = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'body' => $request->input('body'),
            'ip' => $request->ip()
        ];

        $create = Contact::create($saveData);
        if ($create instanceof Contact) {
            return response([
                'status' => 200,
                'message' => Message::SuccessMessageContact,
            ]);
        } else {
            return response([
                'status' => 102,
                'message' => Message::ErrorMessageContact,
            ]);
        }
    }


    public function saveNewsLatter(Request $request)
    {
        $email = $request->input('newsLatter');


        $saveData = [
            'email' => $email
        ];
        if (isset($email) && $email != "" && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $checkEmail = NewsLatters::where('email', $email)->first();
            if (isset($checkEmail) && !empty($checkEmail)) {
                return response([
                    'status' => 102,
                    'message' => 'Your email is already registered with the system \n Thanks',
                ]);
            } else {
                $create = NewsLatters::create($saveData);
                if ($create instanceof NewsLatters) {
                    return response([
                        'status' => 200,
                        'message' => 'Your email is already registered with the system \n Thanks',
                    ]);
                } else {
                    return response([
                        'status' => 102,
                        'message' => 'Please enter the appropriate information',
                    ]);

                }
            }
        } else {
            return response([
                'status' => 102,
                'message' => 'Please enter your email',
            ]);
        }

    }

    /*Search in Product (header website)*/
    public function SearchHeader(Request $request)
    {

        $title = $request->title;
        $category = $request->category;
        $findCategory = Category::find($category);

        $products = Product::with(['variations', 'image'])->latest()->when($title, function ($query) use ($title) {
            $query->where('title', 'like', '%' . $title . '%');
        })->when($category, function ($query) use ($category) {
            $query->where('category_id', '=', $category);
        })->paginate(9);

        SEO::setTitle('جستجو');
        return response([
            'status' => 200,
            'data' => [
                'products' => $products,
                'findCategory' => $findCategory,
                'title' => $title,
            ],
            'message' => 'success',
        ]);
    }

    /** Single Search Product**/
    public function SingleSearchProduct(Request $request)
    {
        $title = $request->title;

        $products = Product::with(['image'])->where('title', 'like', '%' . $title . '%')->latest()->paginate(2);
        return response([
            'status' => 201,
            'data' => [
                'products' => $products,
            ],
            'message' => 'success',
        ]);
    }

    /** list Product base on Category*/
    public function CategoryList($slug = null)
    {

        if (!empty($slug)) {
            $category = Category::with(['image', 'attributes' => function ($query) {
                $query->where('is_filter', 1);
            }])->where('slug', $slug)->firstOrfail();

            if (isset($category->subCategory) && count($category->subCategory) > 0) {
                return response([
                    'status' => 200,
                    'data' => [
                        'categorys' => $category->subCategory()->with('image')->where('status', 1)->get(),
                    ],
                    'message' => 'success',
                ]);
            }
            $attributes = self::checkAttrAndAttrGroupForCategoryProduct($category);
            $products = Product::with('image', 'variations')->whereStatus(1)->where('category_id', $category->id)->paginate(2);
            $variations = Array();
            foreach ($products as $product) {
                $variation = $product->variations->pluck('attribute_type_value_id')->toArray();
                foreach ($variation as $itemVar) {
                    array_push($variations, $itemVar);
                }
            }
            $variations = array_unique($variations);

            $brands = Brand::select('title', 'id')->whereStatus(1)->whereIn('id', $products->pluck('brand_id')->toArray())->get();
            $attrType = AttributeType::with(['attributeTypeValue' => function ($q) use ($variations) {
                $q->whereIn('id', $variations);
            }])->where('id', '<>', 3)->get();

            return response([
                'status' => 201,
                'data' => [
                    'products' => $products,
                    'category' => $category,
                    'attributes' => $attributes,
                    'brands' => $brands,
                    'attrType' => $attrType,
                ],
                'message' => 'success',
            ]);
        } else {

            $categorys = Category::query()->whereType(Product::class)->with(['image'])->where('parent_id', 0)->latest()->get();
            return response([
                'status' => 200,
                'data' => [
                    'categorys' => $categorys,
                ],
                'message' => 'success',
            ]);
        }

    }
    /** list Product base on Category*/

    /** Start Function Filter Base On Attr and Attr Value**/
    public function FilterBaseOnAttr(Request $request)
    {
        $categoryProduct = $request->input('categoryProduct');
        $brand = $request->input('brand');
        $category = $request->input('category');
        $attribute = $request->input('attr');
        $attributeValue = $request->input('attributeValue');
        $variations = $request->input('variations');
        $selected = [];


        // Start Of validation Brand
        if (isset($brand) && !empty($brand)) {
            $findBrand = Brand::whereStatus(1)->findOrfail($brand);
            array_push($selected, $findBrand->title);
        }
        // End Of validation Brand

        if (isset($attribute) && !empty($attribute)){
            foreach ($attribute as $itemValue) {
                if (isset($itemValue) && !empty($itemValue)) {
                    $FindAttrValue = AttributeValue::find($itemValue);
                    $attrValues = AttributeValue::where('value', $FindAttrValue->value)->get();
                    foreach ($attrValues as $itemAttrValue) {
                        if (isset($itemAttrValue)){
                            array_push($selected, $itemAttrValue->value);
                        }
                    }
                }
            }
        }

        $selected = array_unique($selected);


        // validation category Products
        $categoryProductFind = Category::findOrFail($categoryProduct);

        $products = Product::with('image')->whereStatus(1)->where('category_id', $categoryProduct)->
//        whereHas('variations', function ($vQuery) use ($variations) {
//            $vQuery->whereIn('attribute_type_value_id',$variations);
//        })->orWhereHas('attributevalues', function ($attrQuery) use ($attribute, $attributeValue) {
//            $attrQuery->whereIn('value', $attributeValue);
//            $attrQuery->whereIn('attribute_value_id',$attribute);
//        })->
        when($brand, function ($query) use ($brand) {
            $query->where('brand_id', $brand);
        })->
        latest()->paginate(1);

        return response()->json([
            'products' => $products,
            'selected' => $selected,
        ]);
    }
    /** End Function Filter Base On Attr and Attr Value**/

    /** list Product base on Brand*/
    public function BrandList($slug = null)
    {
        if (!empty($slug)) {
            $brand = Brand::where('slug', $slug)->firstOrfail();
            $products = Product::with('image', 'variations')->whereStatus(1)->where('brand_id', $brand->id)->paginate(12);
            SEO::setTitle($brand->title);
            OpenGraph::addImage(['url' => Url($brand->image[0]->url), 'size' => 300]);
            return response([
                'status' => 200,
                'data' => [
                    'products' => $products,
                    'brand' => $brand,
                ],
                'message' => 'success',
            ]);
        } else {
            return Redirect::to('/', 301);
        }

    }
    /** list Product base on Brand*/

    /*Start Of Save Comment */
    public function SaveComment(Request $request)
    {


        if (\auth()->check()) {


            $validator = Validator::make($request->all(), [
                'comment' => 'required|min:5',

            ]);

            if ($validator->fails()) {
                return response([
                    'status' => 300,
                    'error' => $validator->errors()->all(),
                    'message' => 'validation error',
                ]);
            }


            $user_id = Auth::user()->id;
            $parent_id = $request->input('parent_id');
            $id = $request->input('commentable_id');
            $class = $request->input('commentable_type');

            /* check class */
            if (!class_exists($class)) {
                return response([
                    'status' => 102,
                    'message' => 'Your message has failed, please try again.',
                ]);

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
                    return response([
                        'status' => 200,
                        'message' => 'Your comment has been recorded.',
                    ]);

                } else {
                    return response([
                        'status' => 102,
                        'message' => 'Please enter the appropriate information',
                    ]);

                }
            } else {
                return response([
                    'status' => 102,
                    'message' => Message::illegalError,
                ]);
            }
        } else {
            return response([
                'status' => 101,
                'message' => 'Please log in first',
            ]);
        }
    }
    /*End Of Save Comment*/


    /* start activation user */
    public function activation($token)
    {

        $activationCode = Activation::whereCode($token)->first();

        /* exist or not */
        if (!$activationCode) {
            return response([
                'status' => 102,
                'message' => 'Your time is up, please try again in a few moments',
            ]);
        }

        /* expire */
        if ($activationCode->expire < Carbon::now()) {
            return response([
                'status' => 102,
                'message' => 'Your time is up, please try again in a few moments',
            ]);
        }

        /* used */
        if ($activationCode->used == true) {
            return response([
                'status' => 102,
                'message' => 'Your time is up, please try again in a few moments',
            ]);

        }

        $activationCode->update([
            'used' => true,
        ]);

        $activationCode->user()->update([
            'active' => 1
        ]);

        auth()->loginUsingId($activationCode->user->id);

        return response([
            'status' => 200,
            'message' => 'You are logged in correctly. \n Thank you',
        ]);

    }
    /* end activation user */

    /* Start Of AddFavorites*/
    public function AddFavorites(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'id' => 'numeric|required',
        ]);
        if ($validator->fails()) {
            return response([
                'status' => 300,
                'error' => $validator->errors()->all(),
                'message' => 'validation error',
            ]);
        }


        $id = $request->id;
        $product = Product::findOrfail($id);
        $ids = $product->id;

        if (\auth()->check()) {
            $user_id = Auth::user()->id;
            if ($product->count() > 0) {
                /* check class */
                $class = get_class($product);
                if (!class_exists($class)) {
                    return response([
                        'status' => 100,
                        'msg' => 'No product found !!'
                    ]);
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
                            return response([
                                'status' => 101,
                                'msg' => 'Selected product is in your wishlist'
                            ]);
                        } else {
                            $productfavorite->favorites()->create([
                                'user_id' => $user_id,
                                'favoriteable_id' => $productfavorite->id,
                                'favoriteable_type ' => get_class($productfavorite)
                            ]);
                            return response([
                                'status' => 200,
                                'msg' => 'The product you are looking for has been added to your interest'
                            ]);
                        }


                    } else {
                        return response([
                            'status' => 100,
                            'msg' => 'No product found!'
                        ]);
                    }
                } else {
                    return response([
                        'status' => 100,
                        'msg' => 'Please select the product correctly'
                    ]);
                }

            }
        } else {
            return response([
                'status' => 100,
                'msg' => 'Sign in to add favorites first'
            ]);
        }
    }

    /* end Of AddFavorites*/

    public function CustomOrder()
    {
        SEO::setTitle('Submit an Order');
        return view('site.order');
    }

    public function CustomOrderSave(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'link.*' => "required",
            'description.*' => "required",
        ]);

        if ($validator->fails()) {
            return response([
                'status' => 300,
                'error' => $validator->errors()->all(),
                'message' => 'validation error',
            ]);
        }


        $links = $request->input('link');
        $descriptions = $request->input('description');
        if (\auth()->check()) {
            if (count($links) == count($descriptions)) {
                $trackingCode = env('PERFIX') . time();
                $productRequest = ['user_id' => \auth()->user()->id, 'tracking_code' => $trackingCode];
                $saveProductRequest = ProductRequest::create($productRequest);
                if ($saveProductRequest instanceof ProductRequest) {
                    $resultSaveOrderItem = $this->SaveOrderItem($links, $descriptions, $saveProductRequest);
                    if ($resultSaveOrderItem == true) {
                        return response([
                            'status' => 200,
                            'message' => "Thanks, your order has been registered.\n tracking code :$trackingCode",
                        ]);

                    } else {
                        return response([
                            'status' => 102,
                            'message' => 'Error in Ragisteration order...',
                        ]);
                    }
                } else {
                    return response([
                        'status' => 102,
                        'message' => 'Error in Ragisteration order...',
                    ]);
                }
            } else {
                return response([
                    'status' => 102,
                    'message' => 'There was a change in form',
                ]);
            }
        } else {
            return response([
                'status' => 102,
                'message' => 'Please log in first to place an order',
            ]);

        }

    }

    private function SaveOrderItem($links, $descriptions, $saveProductRequest)
    {
        $flag = true;
        foreach ($links as $key => $value) {
            $itemLink = $value;
            $itemDescriptions = $descriptions[$key];
            $productRequestItem = [
                'product_request_id' => $saveProductRequest->id,
                'link' => $itemLink,
                'description' => $itemDescriptions,
            ];
            $saveProductRequestItem = ProductRequestItem::create($productRequestItem);
            if ($saveProductRequestItem instanceof ProductRequestItem) {
                $flag = true;
            } else {
                $flag = false;
                return $flag;
            }
        }
        return $flag;
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

}
