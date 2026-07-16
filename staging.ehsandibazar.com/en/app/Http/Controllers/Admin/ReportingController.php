<?php

namespace App\Http\Controllers\Admin;

use App\Model\Category;
use App\Model\OrderItem;
use App\Model\Product;
use App\Utility\Status;
use Hekmatinasser\Verta\Verta;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use SEO;

class ReportingController extends Controller
{
    protected $allCategoryProducts;
    public $product;

    public function __construct()
    {
        parent::__construct();
        $this->product = Product::query()->select('id','title')->get();
    }

    public function index()
    {
        $title = "گزارش گیری";
        SEO::setTitle($title);
        $products = $this->product;
    
       $allCategoryProducts = DB::table('categories as c1')
    ->join('categories as c2', 'c1.parent_id', '=', 'c2.id')
    ->where('c1.status', '=', 1)->where('c1.parent_id', '!=', 0)->select('c1.id', 'c1.title as title','c2.title as titleparent')
            ->get();
 
        return view('panel.reporting.index', compact('products','allCategoryProducts','title'));
    }

    public function report(Request $request)
    {
       
        $productId = $request->input('product');
        $categoryId = $request->input('category');
     
        $startDateInput = $request->input('start_date');
        $endDateInput = $request->input('end_date');
        $start_date = $this->convertToMiladi($startDateInput);
        $end_date = $this->convertToMiladi($endDateInput);

       if($categoryId){
           $categories_id=array();
           $category_data = Category::findOrFail($categoryId);
          
              if($category_data->subCategory){
                 $categories_id= $category_data->subCategory()->pluck('id')->toArray();
                  
              }
             
              array_push($categories_id, $categoryId);
          
            $orders = OrderItem::whereHas('product',function ($query) use ($categories_id) {
      
                $query->whereIn('category_id',$categories_id);
            })->
            whereHas('order',function ($q){
                $q->where('status',Status::SENDING);
            })->
            when($productId, function ($query) use ($productId) {
                $query->whereIn('product_id', $productId);
            })->
            when([$start_date, $end_date], function ($query) use ($start_date, $end_date) {
                $query->whereBetween('created_at', [$start_date . " 00:00:00", $end_date . " 23:59:59"]);
            })->
            orderBy('product_id','desc')->get();
       }else{
           $category_data="";
           $orders = OrderItem::
            whereHas('order',function ($q){
                $q->where('status',Status::SENDING);
            })->
            when($productId, function ($query) use ($productId) {
                $query->whereIn('product_id', $productId);
            })->
            when([$start_date, $end_date], function ($query) use ($start_date, $end_date) {
                if (isset($start_date) && !empty($start_date)){
                    $query->whereBetween('created_at', [$start_date . " 00:00:00", $end_date . " 23:59:59"]);
                }
            })->
            orderBy('product_id','desc')->get();
       }
        $products = $this->product;
          $allCategoryProducts = DB::table('categories as c1')
    ->join('categories as c2', 'c1.parent_id', '=', 'c2.id')
    ->where('c1.status', '=', 1)->where('c1.parent_id', '!=', 0)->select('c1.id', 'c1.title as title','c2.title as titleparent')
            ->get();
        return view('panel.reporting.index', compact('orders', 'products','allCategoryProducts','category_data','startDateInput','endDateInput'));


    }

    private function convertToMiladi($date)
    {
        if (isset($date) && !empty($date)) {
            $explodeDate = explode("/", $date);
            if (count($explodeDate) == 3) {
                $times = explode(" ", $explodeDate[2]);
                $year = $this->convert2english($explodeDate[0]);
                $month = $this->convert2english($explodeDate[1]);
                $day = $this->convert2english($times[0]);

                $miladi = Verta::getGregorian($year, $month, $day); // [2015,12,25]
                $stringMiladi = $miladi[0] . "-" . $miladi[1] . "-" . $miladi[2];
//                $stringMiladi = $miladi[0] . "-" . $miladi[1] . "-" . $miladi[2] . " " . $this->convert2english($times[1]);
                return $stringMiladi;
//                return $timestamp = strtotime($stringMiladi);
            } else {
                return false;
            }
        }
    }

    public function convert2english($string)
    {
        $newNumbers = range(0, 9);
        // 1. Persian HTML decimal
        $persianDecimal = array('&#1776;', '&#1777;', '&#1778;', '&#1779;', '&#1780;', '&#1781;', '&#1782;', '&#1783;', '&#1784;', '&#1785;');
        // 2. Arabic HTML decimal
        $arabicDecimal = array('&#1632;', '&#1633;', '&#1634;', '&#1635;', '&#1636;', '&#1637;', '&#1638;', '&#1639;', '&#1640;', '&#1641;');
        // 3. Arabic Numeric
        $arabic = array('٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩');
        // 4. Persian Numeric
        $persian = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');

        $string = str_replace($persianDecimal, $newNumbers, $string);
        $string = str_replace($arabicDecimal, $newNumbers, $string);
        $string = str_replace($arabic, $newNumbers, $string);
        return str_replace($persian, $newNumbers, $string);
    }
}
