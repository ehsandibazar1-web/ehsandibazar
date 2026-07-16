<?php

namespace App\Http\Controllers\Api\v1\auth;

use App\Events\eventSmsRegister;
use App\Model\Activation;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ForgetPasswordController extends Controller
{
    /* send sms */
    public function SendSmsResetPassword(Request $request)
    {
        $mobile = $request->input("mobile");
        $this->validate($request, [
            'mobile' => 'required'
        ]);
        $findMobile = User::whereMobile($mobile)->first();

        if (isset($findMobile) && !empty($findMobile)) {
            if ($findMobile->active <= 0) {
                return response([
                    'status' => 199,
                    'message' => 'our account is not active'
                ]);
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
                                return response([
                                    'status' => 199,
                                    'message' => 'The code has been sent to the requested mobile number and you cannot request it again in' . $diff_in_minutes . 'second'
                                ]);
                            }
                        }
                        return response([
                            'status' => 199,
                            'message' => 'The code has been sent to the requested mobile number and cannot be resubmitted in ' . $diff_in_minutes . 'minute'
                        ]);


                    } else {
                        // check mobile in activation code
                        event(new eventSmsRegister($findMobile));
                        return response([
                            'status' => 199,
                            'message' => 'You will be sent an SMS with a verification code'
                        ]);
                    }

                } else {
                    // check mobile in activation code
                    event(new eventSmsRegister($findMobile));
                    return response([
                        'status' => 200,
                        'message' => 'You will be sent an SMS with a verification code.'
                    ]);
                }

            } else {
                event(new eventSmsRegister($findMobile));
                return response([
                    'status' => 200,
                    'message' => 'You will be sent an SMS with a verification code.'
                ]);
            }

        } else {
            return response([
                'status' => 404,
                'message' => 'user Not Found...'
            ]);
        }

    }

    /* update pass */
    public function updatePassword(Request $request)
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
            'password' => ['required', 'min:6', 'max:100'],
        ]);

        $findMobile = User::whereMobile($mobile)->first();

        if (isset($findMobile) && !empty($findMobile)) {

            $findCode = Activation::where('code', $code)->where('mobile', $mobile)->latest()->first();

            if (isset($findCode) && !empty($findCode)) {

                if ($findCode->used > 0) {
                    return response([
                        'status' => 404,
                        'message' => 'This code has expired'
                    ]);
                }

                if ($findCode->used == 0) {

                    $expire = new Carbon($findCode->expire);
                    $expire = $expire->getTimestamp();
                    $now = new Carbon();
                    $now = $now->getTimestamp();

                    if ($expire >= $now) {

                        $activationUpdate = Activation::where('user_id', $findMobile->id)
                            ->where('mobile', $findMobile->mobile)
                            ->where('code', $findCode->code)
                            ->first()
                            ->update(['used' => 1]);
                        if ($activationUpdate > 0) {
                            $updateUser = User::where('id', $findMobile->id)->first()
                                ->update(['password' => Hash::make($password)]);
                            if ($updateUser > 0) {
                                return response([
                                    'status' => 200,
                                    'message' => 'Your password has been changed correctly'
                                ]);
                            }
                        }

                    } else {
                        return response([
                            'status' => 200,
                            'message' => 'Your time is up, please try again'
                        ]);

                    }

                }

            } else {
                return response([
                    'status' => 404,
                    'message' => 'No code found'
                ]);

            }

        } else {
            return response([
                'status' => 404,
                'message' => 'No user found with this profile.'
            ]);

        }

    }
}
