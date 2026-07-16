<?php

namespace App\Http\Controllers\Admin;

use App\Model\Order;
use App\Model\Systeminfmanage;
use App\Model\Comment;
use App\Model\Tag;
use App\User;
use App\Model\Video;
use App\Utility\paymentMethods;
use App\Utility\Status;
use Arcanedev\LogViewer\Http\Controllers\LogViewerController;
use Illuminate\Support\Str;
use SEO;

class AdminController extends LogViewerController
{

    public function dashboard()
    {
        $title = "پنل مدیریت";
        SEO::setTitle($title);
        $paidPayment = Order::where('status', Status::PAID)->count();

        $pendingPayment = Order::where('status', Status::PENDING)->count();
        $unPaidPayment = Order::where('status', Status::UNPAID)->count();
        $canceledPayment = Order::where('status', Status::CANCELED)->count();
        $returnPayment = Order::where('status', Status::RETURNED)->count();
        $waitingPayment = Order::where('status', Status::WAITING)->count();
        $onlinePayment = Order::where('payment_method_id', paymentMethods::ONLINE)->count();
        $users = User::count();
        return view('panel.index', compact('paidPayment', 'pendingPayment', 'unPaidPayment',
            'canceledPayment', 'returnPayment', 'waitingPayment', 'onlinePayment', 'users'));
    }

    public static function createTags($request)
    {
        $getDataTages = $request->input('tags');
        $tags = [];
        if (isset($getDataTages) && !empty($getDataTages)) {
            $getDataTages = explode(",", $request->input('tags'));
            foreach ($getDataTages as $itemTag) {
                $tag = Tag::firstOrCreate(['title' => $itemTag, 'status' => Status::active]);
                array_push($tags, $tag->id);
            }
        }
        return $tags;
    }

    public static function createSeo($request,$model)
    {
        $metaTitle = $request->input('metaTitle') ?: $model->title;
        $metaDescription = $request->input('metaDescription',Str::limit($model->title,200));
        $metaKeyword = $request->input('metaKeyword');
        $metaCanonical = $request->input('metaCanonical');
        $method= isset($model->seo) && !empty($model->seo) ? 'update' : 'create';
        return $model->seo()->{$method}([
            'title' => $metaTitle,
            'description' => $metaDescription,
            'keyword' => $metaKeyword,
            'canonical' => $metaCanonical,
        ]);
    }

}
