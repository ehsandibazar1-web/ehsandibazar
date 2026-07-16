<?php

namespace App\Http\Controllers\Auth;

use App\Events\eventSmsRegister;
use App\Http\Controllers\Controller;
use App\Model\Activation;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use SEOMeta;
use OpenGraph;
use Twitter;
## or
use SEO;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /* show */
    public function updatePasswordSms()
    {
        SEO::setTitle('بازیابی رمز عبور');
        return view('auth.resetPasswordBySms.resetPassBySms');
    }

    /* send sms */
    public function updatePasswordSendSms(Request $request)
    {
        $mobile = $request->input("mobile");
        $this->validate($request, [
            'mobile' => 'required'
        ]);
        $findMobile = User::whereMobile($mobile)->first();

        if (isset($findMobile) && !empty($findMobile)) {
            if ($findMobile->active <= 0) {
                alert()->error('متاسفیم!',' حساب کاربری شما فعال نمی باشد ')->showConfirmButton('بستن');
                return redirect()->route('login');
            }

            $findActivationForMobile = Activation::where('mobile', $findMobile->mobile)->latest()->first();

            if (isset($findActivationForMobile)) {

                if ($findActivationForMobile->used == 0) {

                    // check time

                    $expire = new Carbon($findActivationForMobile->expire);
                    $expire = $expire->getTimestamp();
                    $now = new Carbon();
                    $now = $now->getTimestamp();
                    /*if ($expire >= $now) {

                        $vertas = new \Hekmatinasser\Verta\Verta();
                        $now = Carbon::now()->timestamp;
                        $timeStamepNow = $vertas->createTimestamp($now);
                        $timeStamp15 = $vertas->createTimestamp($expire);
                        $diff_in_minutes = $timeStamepNow->diffMinutes($timeStamp15);
                        if ($diff_in_minutes == 0) {
                            if ($diff_in_minutes == 0) {
                                $diff_in_minutes = $timeStamepNow->diffSeconds($timeStamp15);
                                alert()->error('متاسفیم!','کد به شماره موبایل درخواستی ارسال شده است و تا ' . $diff_in_minutes . ' ثانیه دیگر نمی توانید در خواست مجدد دهید')->showConfirmButton('بستن');
                                return back();
                            }
                        }

                        alert()->error('متاسفیم!','کد به شماره موبایل درخواستی ارسال شده است و تا ' . $diff_in_minutes . ' دقیقه دیگر نمی توانید در خواست مجدد دهید')->showConfirmButton('بستن');
                        return back();

                    } else {*/
                        // check mobile in activation code
                        event(new eventSmsRegister($findMobile));
                        alert()->success('موفقیت آمیز!','پیامکی حاوی کد تایید برای شما ارسال خواهد شد.')->showConfirmButton('بستن');
                        return redirect()->route('reset.password.send.sms.change');
                    /*}*/

                } else {
                    // check mobile in activation code
                    event(new eventSmsRegister($findMobile));
                    alert()->success('موفقیت آمیز!','پیامکی حاوی کد تایید برای شما ارسال خواهد شد.')->showConfirmButton('بستن');
                    return redirect()->route('reset.password.send.sms.change');
                }

            } else {
                event(new eventSmsRegister($findMobile));
                alert()->success('موفقیت آمیز!','پیامکی حاوی کد تایید برای شما ارسال خواهد شد.')->showConfirmButton('بستن');
                return redirect()->route('reset.password.send.sms.change');
            }

        } else {
            alert()->error('متاسفیم!','کاربری با این مشخصات یافت نشد. ')->showConfirmButton('بستن');
            return back();
        }

    }

    /* view change pass */
    public function updatePasswordSmsChange()
    {
        SEO::setTitle('بازیابی رمز عبور');
        return view('auth.resetPasswordBySms.changePassBySms');
    }

    /* update pass */
    public function updatePasswordSmsChangeStore(Request $request)
    {
        $mobile = $request->input('mobile');
        $code = $request->input('code');
        $password = $request->input('password');

        $this->validate($request, [
            'mobile' => [
                'required',
                'numeric',
                'regex:/0{1}9{1}[0-9]{9}/',
            ],
            'code' => "required",
            'password' => ['required', 'min:6', 'max:100', 'confirmed'],
        ]);

        $findMobile = User::whereMobile($mobile)->first();

        if (isset($findMobile) && !empty($findMobile)) {

            $findCode = Activation::where('code', $code)->where('mobile', $mobile)->latest()->first();

            if (isset($findCode) && !empty($findCode)) {


                if ($findCode->used > 0) {

                    alert()->error('متاسفیم!','این کد منقضی شده است')->showConfirmButton('بستن');
                    return redirect()->route('reset.password.update.sms.view');
                }

                if ($findCode->used == 0) {

                    $expire = new Carbon($findCode->expire);
                    $expire = $expire->getTimestamp();
                    $now = new Carbon();
                    $now = $now->getTimestamp();

                    if ($expire >= $now) {

                        $activationUpdate = Activation::where('user_id', $findMobile->id)->first()
                            ->update(['used' => 1]);
                        if ($activationUpdate > 0) {
                            $updateUser = User::where('id', $findMobile->id)->first()
                                ->update(['password' => Hash::make($password)]);
                            if ($updateUser > 0) {
                                Auth::loginUsingId($findMobile->id);
                                alert()->success('موفقیت آمیز','رمز عبور شما به درستی تغییر کرد.')->showConfirmButton('بستن');
                                return redirect()->route('site.index');
                            }
                        }

                    } else {
                        alert()->error('متاسفیم','وقت شما به پایان رسیده است , لطفا دوباره امتحان فرمایید')->showConfirmButton("بستن");
                        return redirect()->route('reset.password.update.sms.view');
                    }

                }

            } else {
                alert()->error('متاسفیم!','کدی یافت نشد')->showConfirmButton('بستن');
                return back();
            }

        } else {
            alert()->error('متاسفیم!','کاربری با این مشخصات یافت نشد.')->showConfirmButton('بستن');
            return back();
        }

    }

}
