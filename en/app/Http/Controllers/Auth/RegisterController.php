<?php

namespace App\Http\Controllers\Auth;

use App\Events\EmailActivation;
use App\Events\eventSendSmsRegister;
use App\Events\eventSmsRegister;
use App\Model\Activation;
use App\Model\Address;
use App\Model\City;
use App\Model\Detail;
use App\Model\Province;
use App\Services\ImageServices\ImageServices;
use App\User;
use App\Http\Controllers\Controller;
use App\Utility\Level;
use App\Utility\UploadImages;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Validation\Rule;
use App\Utility\SendSms;
use SEOMeta;
use OpenGraph;
use Twitter;

## or
use SEO;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        SEO::setTitle('register');
        return view('auth.register');
    }

    public function showRegistrationColleagueForm()
    {
        SEO::setTitle('ثبت نام همکاران');
        return view('auth.register-colleague');
    }

    protected function validator(array $data, $userType = 0)
    {
        if ($userType == 0) {
            // General User...
            return Validator::make($data, [
                'name' => ['required', 'string', 'max:190'],
                'family' => ['required', 'string', 'max:190'],
                'email' => [
                    'required',
                ],
                'password' => ['required', 'string', 'min:6', 'max:100', 'confirmed'],
                'low' => "required"
            ]);
        } else {
            // Colleague user...
            return Validator::make($data, [
                'name' => ['required', 'string', 'max:190'],
                'family' => ['required', 'string', 'max:190'],
                'mobile' => [
                    'required',
                    'numeric',
                    'regex:/0{1}9{1}[0-9]{9}/',
                    Rule::unique('users', 'mobile')->where(function ($query) {
                        $query->where('deleted_at', null);
                    }),
                ],
                'national_code' => ['required', 'max:190'],
                'economic_code' => ['required', 'max:190'],
                'tell' => ['required', 'max:190'],
                'address' => ['required', 'string', 'max:190'],
                'password' => ['required', 'string', 'min:6', 'max:100', 'confirmed'],
                'low' => "required"
            ]);
        }
    }

    protected function create(array $data)
    {
        $requestData = [
            'name' => $data['name'],
            'family' => $data['family'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'level' => isset($data['level']) && !empty($data['level']) ? $data['level'] : Level::USER,
            'tell' => isset($data['tell']) && !empty($data['tell']) ? $data['tell'] : null,
            'national_code' => isset($data['national_code']) && !empty($data['national_code']) ? $data['national_code'] : null,
            'economic_code' => isset($data['economic_code']) && !empty($data['economic_code']) ? $data['economic_code'] : null,
            'address' => isset($data['address']) && !empty($data['address']) ? $data['address'] : null,
            'block' => isset($data['level']) && !empty($data['level']) ? 1 : 0
        ];
        // return User::create($requestData);
        $user = User::create($requestData);
        $user->roles()->sync(3);
        return $user;
    }

    public function register(Request $request)
    {

        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        /* send sms */
        // event(new eventSmsRegister($user));

        // return redirect()->route('activation.user.view');

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());

    }

    public function registerColleague(Request $request)
    {
        $this->validator($request->all(), 1)->validate();
        $request->merge(['level' => Level::COLLEAGUE]);
        event(new Registered($user = $this->create($request->all())));

        /* send sms */
        event(new eventSmsRegister($user));

        return redirect()->route('activation.user.view');
    }

    /* view active mobile */
    public function activationView()
    {
        SEO::setTitle(env('STORE_NAME') . " | " . "فعالسازی");
        if (isset(\auth()->user()->id)) {
            return redirect()->route('site.index');
        }
        return view('auth.verifyMobile');
    }

    /* post active mobile */
    public function activation(Request $request)
    {

        $mobile = $request->input('mobile');
        $activationCode = $request->input('activationCode');

        $this->validate($request, [
            'mobile' => [
                'required',
                'numeric',
                'regex:/0{1}9{1}[0-9]{9}/',
            ],
            'activationCode' => 'required|numeric'
        ]);

        if (isset($mobile) && isset($activationCode)) {

            $findMobile = User::whereMobile($mobile)->first();

            if (isset($findMobile) && !empty($findMobile)) {
                $findCodeActivation = Activation::whereCode($activationCode)->whereMobile($mobile)->latest()->first();
                if (isset($findCodeActivation) && !empty($findCodeActivation)) {

                    if ($findCodeActivation->used > 0) {
                        alert()->error(Lang::get('cms.error'),'این کد استفاده شده است')->showConfirmButton('بستن');
                        return back();
                    }

                    $expire = new Carbon($findCodeActivation->expire);
                    $expire = $expire->getTimestamp();
                    $now = new Carbon();
                    $now = $now->getTimestamp();

                    if ($expire >= $now) {

                        $activationUpdate = Activation::where('user_id', $findMobile->id)->first()
                            ->update(['used' => 1]);
                        if ($activationUpdate > 0) {
                            $updateUser = User::where('id', $findMobile->id)->first()
                                ->update(['active' => 1]);
                            if ($updateUser > 0) {
                                Auth::loginUsingId($findMobile->id);
                                alert()->success('موفقیت آمیز','شما به درستی وارد سایت شدید.')->showConfirmButton('بستن');
                                return redirect()->route('site.index');
                            }

                        }

                    } else {
                        alert()->error('متاسفیم','وقت شما به پایان رسیده است , لطفا دوباره امتحان فرمایید')->showConfirmButton("بستن");
                        return redirect()->route('send.activation.code.again');
                    }


                } else {

                    alert()->error('متاسفیم','کد وارد شده اشتباه می باشد.')->showConfirmButton("بستن");
                    return redirect()->route('activation.user.view');
                }
            } else {
                alert()->error('متاسفیم','کاربری یافت نشد,لطفا در وارد کردن اطلاعات دقت فرمایید.')->showConfirmButton("بستن");
                return redirect()->route('activation.user.view');
            }

        }

    }

    /* activation code send again */
    public function sendCodeAgain()
    {
        SEO::setTitle(env('STORE_NAME') . " | " . "ارسال کد فعالسازی");
        return view('auth.sendCodeAgain');
    }

    /* send request activation code */
    public function sendCodeRequest(Request $request)
    {
        $mobile = $request->input('mobile');

        $this->validate($request, [
            'mobile' => 'required',
        ]);

        $findMobile = User::whereMobile($mobile)->first();

        if (isset($findMobile) && !empty($findMobile)) {
            if ($findMobile->active == 0) {
                if (isset($findMobile) && !empty($findMobile)) {

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
                                        alert()->error('متاسفیم!','کد به شماره موبایل درخواستی ارسال شده است و تا ' . $diff_in_minutes . ' ثانیه دیگر نمی توانید در خواست مجدد دهید')->showConfirmButton('بستن');
                                        return back();
                                    }
                                }

                                alert()->error('متاسفیم!','کد به شماره موبایل درخواستی ارسال شده است و تا ' . $diff_in_minutes . ' دقیقه دیگر نمی توانید در خواست مجدد دهید')->showConfirmButton('بستن');
                                return back();

                            } else {
                                event(new eventSmsRegister($findMobile));
                                return redirect()->route('activation.user.view');
                            }

                        } else {
                            event(new eventSmsRegister($findMobile));
                            return redirect()->route('activation.user.view');
                        }

                    } else {
                        event(new eventSmsRegister($findMobile));
                        return redirect()->route('activation.user.view');
                    }

                } else {
                    alert()->error('متاسفیم!','کاربری با این مشخصات یافت نشد لطفا از قسمت ثبت نام امتحان فرمایید.')->showConfirmButton('بستن');
                    return redirect()->route('register');
                }
            } else {
                alert()->error('متاسفیم!','اکانت شما فعال می باشد لطفا از قسمت ورود امتحان فرمایید.')->showConfirmButton('بستن');
                return redirect()->route('login');
            }
        } else {
            alert()->error('متاسفیم!','شماره موبایل وجود ندارد ، لطفا موبایل صحیح را وارد نمایید')->showConfirmButton('بستن');
            return back();
        }

    }

}
