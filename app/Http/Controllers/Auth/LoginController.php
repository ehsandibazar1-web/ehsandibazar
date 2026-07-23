<?php

namespace App\Http\Controllers\Auth;

use App\Events\EmailActivation;
use App\Events\eventActivation;
use App\Events\eventSmsRegister;
use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use SEOMeta;
use OpenGraph;
use Twitter;
## or
use SEO;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function redirectTo()
    {
        $user = auth()->user();

        // ادمین/سوپرادمین → پنلِ جدیدِ Filament. فقط همین‌ها canAccessPanel دارند
        // (متدِ User::canAccessPanel = isSuperAdminOrAdmin)، پس دقیقاً همین شرط تا حلقه‌ی ۴۰۳ رخ ندهد.
        if ($user->isSuperAdminOrAdmin()) {
            return '/adminpanel';
        }

        // اپراتور و سایرِ کارکنانِ پنلِ قدیمی که هنوز به پنلِ جدید دسترسی ندارند → پنلِ قدیمی.
        if ($user->isAdmin() || $user->isOperator()) {
            return 'panel/manager/';
        }

        // مشتریِ عادی → داشبوردِ کاربریِ خودش (بدونِ تغییر).
        return '/panel/users/';
    }
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        SEO::setTitle('ورود');
        return view('auth.login');
    }

    protected function validateLogin(Request $request)
    {


        $request->validate([
            $this->username() => 'required|numeric',
            'password' => 'required|string',
        ]);
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        $mobile = $request->input('mobile');
        $findMobile = User::whereMobile($mobile)->whereActive(1)->whereBlock(0)->first();
        if (empty($findMobile)) {
            alert()->error("متاسفیم","حساب کاربری شما در حال بررسی و تایید میباشد \n لطفا تا تایید شدن حساب خود صبور باشید")->showConfirmButton('بستن');
            return redirect()->route('login');
        }


        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }


    public function username()
    {
        return 'mobile';
    }
}
