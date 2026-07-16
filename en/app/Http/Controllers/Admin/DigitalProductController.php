<?php

namespace App\Http\Controllers\Admin;

use App\Model\Product;
use App\User;
use App\Utility\ProductType;
use App\Utility\Status;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use SEO;

class DigitalProductController extends Controller
{
    protected $users;
    public $products;

    public function __construct()
    {
        parent::__construct();
        $this->products = Product::whereStatus(Status::active)->whereIn('type', [ProductType::VOICE, ProductType::PDF, ProductType::VIDEO])->get();
        $this->users = User::get();
    }

    public function index()
    {
        $users = User::whereHas('production')->get();
        $title = "فهرست محصولات دیجیتال خریداری شده";
        SEO::setTitle($title);
        return view('panel.digital-product.index', compact('users','title'));
    }

    public function show(User $user)
    {
        $productions = $user->production;
        $title = "نمایش محصولات دیجیتال ";
        SEO::setTitle($title);
        return view('panel.digital-product.show',compact('user','productions','title'));
    }

    public function add()
    {
        $users = $this->users;
        $products = $this->products;
        $title = "ایجاد سابقه خرید محصول دیجیتال";
        SEO::setTitle($title);
        return view('panel.digital-product.create', compact('users', 'products','title'));
    }

    public function store(Request $request)
    {
        $user = User::findOrFail($request->input('user'));
        $products = $request->input('product');

        foreach ($products as $item){
            $product = Product::findOrFail($item);
            if (isset($user->production[0]) && !empty($user->production[0])) {
                $productUser = $user->production->pluck('id')->toArray();
                if (!in_array($product->id, $productUser)) {
                    $product->users()->attach([$user->id]);
                }
            } else {
                $product->users()->attach([$user->id]);
            }
        }

        toast()->success('با موفقیت انجام شد');
        return back();
    }

    public function delete(Request $request)
    {
        $products = explode(",",$request->input('product'));
        $user = User::find($request->input('userId'));
        foreach ($products as $product){
            $user->production()->detach($product);
        }
        return response()->json(['success'=>"با موفقیت حذف گردید"]);

    }
}
