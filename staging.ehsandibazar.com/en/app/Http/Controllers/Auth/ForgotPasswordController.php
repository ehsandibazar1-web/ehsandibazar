<?php

namespace App\Http\Controllers\Auth;

use App\Events\EmailActivation;
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
        SEO::setTitle('Password recovery');
        return view('auth.resetPasswordBySms.resetPassBySms');
    }

    /* send sms */
    public function updatePasswordSendSms(Request $request)
    {
        $email = $request->input("email");
        $this->validate($request, [
            'email' => 'required'
        ]);
        $findMobile = User::whereEmail($email)->first();

        if (isset($findMobile) && !empty($findMobile)) {
            if ($findMobile->active <= 0) {
                alert()->error('we are sorry!',' Your account is not active ')->showConfirmButton('close');
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
                    if ($expire >= $now) {

                        $vertas = new \Hekmatinasser\Verta\Verta();
                        $now = Carbon::now()->timestamp;
                        $timeStamepNow = $vertas->createTimestamp($now);
                        $timeStamp15 = $vertas->createTimestamp($expire);
                        $diff_in_minutes = $timeStamepNow->diffMinutes($timeStamp15);
                        if ($diff_in_minutes == 0) {
                            if ($diff_in_minutes == 0) {
                                $diff_in_minutes = $timeStamepNow->diffSeconds($timeStamp15);
                                alert()->error('we are sorry!','The code is sent to the requested email and up ' . $diff_in_minutes . ' You can not reapply in seconds')->showConfirmButton('close');
                                return back();
                            }
                        }

                        alert()->error('we are sorry!','The code is sent to the requested email and up  ' . $diff_in_minutes . ' You can not reapply in minutes')->showConfirmButton('close');
                        return back();

                    } else {
                        // check mobile in activation code
                        event(new EmailActivation($findMobile));
                        alert()->success('successful!','An email containing the verification code will be sent to you.')->showConfirmButton('close');
                        return redirect()->route('reset.password.send.sms.change');
                    }

                } else {
                    // check mobile in activation code
                    event(new EmailActivation($findMobile));
                    alert()->success('successful!','An email containing the verification code will be sent to you.')->showConfirmButton('close');
                    return redirect()->route('reset.password.send.sms.change');
                }

            } else {
                event(new EmailActivation($findMobile));
                alert()->success('successful!','An email containing the verification code will be sent to you.')->showConfirmButton('close');
                return redirect()->route('reset.password.send.sms.change');
            }

        } else {
            alert()->error('we are sorry!','No user with this profile was found.')->showConfirmButton('close');
            return back();
        }

    }

    /* view change pass */
    public function updatePasswordSmsChange()
    {
        SEO::setTitle('Password recovery');
        return view('auth.resetPasswordBySms.changePassBySms');
    }

    /* update pass */
    public function updatePasswordSmsChangeStore(Request $request)
    {
        $mobile = $request->input('email');
        $code = $request->input('code');
        $password = $request->input('password');

        $this->validate($request, [
            'email' => [
                'required',
            ],
            'code' => "required",
            'password' => ['required', 'min:6', 'max:100', 'confirmed'],
        ]);

        $findMobile = User::whereEmail($mobile)->first();

        if (isset($findMobile) && !empty($findMobile)) {

            $findCode = Activation::where('code', $code)->where('mobile', $mobile)->latest()->first();

            if (isset($findCode) && !empty($findCode)) {


                if ($findCode->used > 0) {

                    alert()->error('we are sorry!','This code has expired')->showConfirmButton('close');
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
                                alert()->success('successful','Your password changed correctly.')->showConfirmButton('close');
                                return redirect()->route('site.index');
                            }
                        }

                    } else {
                        alert()->error('we are sorry','Your time is up, please try again')->showConfirmButton("close");
                        return redirect()->route('reset.password.update.sms.view');
                    }

                }

            } else {
                alert()->error('we are sorry!','Code not found')->showConfirmButton('close');
                return back();
            }

        } else {
            alert()->error('we are sorry!','No user with this profile was found.')->showConfirmButton('close');
            return back();
        }

    }

}
