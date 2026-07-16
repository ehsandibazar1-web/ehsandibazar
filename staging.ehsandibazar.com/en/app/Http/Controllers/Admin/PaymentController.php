<?php

namespace App\Http\Controllers\Admin;

use App\Model\Payment;

use App\Http\Controllers\Controller;

use App\Model\Systeminfmanage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{

    protected $user;
    public const countOfRender = 9;
    protected $priceForProduct;

    public function __construct()
    {
        parent::__construct();
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });

        $this->priceForProduct = Systeminfmanage::where('id', 34)->where('status', 1)->first();
    }

    public function index()
    {
        if (isset($this->priceForProduct) && isset($this->priceForProduct->code) && $this->priceForProduct->code > 0) {
            if (!empty($this->priceForProduct)) {
                $priceForProduct = $this->priceForProduct->code;
            } else {
                $priceForProduct = 0;
            }
            return view('panel.payment.index', compact('priceForProduct'));
        }else{
            toastr()->error('فعلا امکان ثبت محصول مقدور نمی باشد.','هشدار!');
            return back();
        }
    }

    public function store(Request $request)
    {
        $numberOfProduct = $request->input('numberOfProduct');
        if (is_numeric($numberOfProduct) && !empty($numberOfProduct) && $numberOfProduct > 0) {
            $priceForProduct = $this->priceForProduct->code;
            return view('panel.payment.checkout', compact('numberOfProduct', 'priceForProduct'));
        } else {
            return back()->with(['error' => 'لطفا تعداد آگهی (محصول) خود را مشخص نمایید.']);
        }
    }

}
