<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $user;
    public const countOfRender = 10;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            $auth = $this->authorizeation();
            if ($auth == true) {
                return $next($request);
            } else {
                toast()->error('شما مجوز لازم را ندارید.', 'خطا!')->showConfirmButton('بستن');
                return back();
            }
        });
    }

    public function authorizeation()
    {
        if (Auth::check()) {
            $result = $this->checkSiteUrl(Route::currentRouteName());
            if ($result == true || $result == null) {
                return true;
            }
            $userPermission = $this->user->roles;

            if (count($userPermission) == 0) {
                header("location: " . \route('logout'));
                exit();
            }
            $currentRouteName = Route::currentRouteName();
            $arrayPermission = [];


            foreach ($userPermission as $itemRole) {
                foreach ($itemRole->permissions as $itemPermission) {
                    $arrayPermission [] = $itemPermission->name;
                }
            }


            if (!in_array($currentRouteName, $arrayPermission)) {
                return false;
            } else {
                return true;
            }
        }
        return true;

    }

    public function checkSiteUrl($url)
    {
        if (isset($url)) {
            $explod = explode(".", $url);
            if (isset($explod[0]) && $explod[0] == "site" || $explod[0] == "users") {
                return true;
            }
        }
    }
}