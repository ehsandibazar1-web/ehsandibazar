<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Address;
use App\Model\City;
use App\Model\Image;
use App\Model\Systeminfmanage;
use App\Services\ImageServices\ImageServices;
use App\User;
use App\Utility\Message;
use App\Utility\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Session;
use SEO;


class ProfileController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        SEO::setTitle('مدیریت | پروفایل من');
        $title = "مدیریت | پروفایل من";
        $profile = Auth::user();
        $address = $profile->address;
        return view('panel.profile.index', compact('profile', 'address','title'));
    }

    public function ChangePw(Request $request)
    {

        if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
            // The passwords matches
            toast()->error('گذرواژه فعلی شما با رمز عبور ارائه شده مطابقت ندارد. لطفا دوباره تلاش کنید.', Lang::get('cms.error'));
            return redirect()->back();
        }
        if (strcmp($request->get('current-password'), $request->get('new-password')) == 0) {
            //Current password and new password are same
            toast()->error('گذرواژه جدید نمی تواند مشابه رمز عبور فعلی شما باشد. لطفاً یک رمز عبور دیگر انتخاب کنید', Lang::get('cms.error'));
            return redirect()->back();
        }
        $validatedData = $request->validate([
            'current-password' => 'required',
            'new-password' => 'required|string|min:6|confirmed',
        ]);
        //Change Password
        $user = Auth::user();
        $user->password = bcrypt($request->get('new-password'));
        $user->save();
        toast()->success('رمز عبور با موفقیت تغییر کرد!', Lang::get('cms.success'));
        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'family' => 'required',
            'email' => 'required',
            'tell' => 'required',
        ]);
        if (is_numeric($id)) {


            /* public information */
            $name = $request->input('name');
            $family = $request->input('family');
            $email = $request->input('email');
            $tell = $request->input('tell');



            $saveData = [
                'name' => $name,
                'family' => $family,
                'email' => $email,
                'tell' => $tell,
            ];

            if (Auth::user()->update($saveData)) {
                toast()->success(Lang::get('cms.success'),'پروفایل با موفقیت ویرایش گردید');
                return redirect()->back();
            } else {
                toast()->error('مشکلی رخ داده است', Lang::get('cms.error'));
                return redirect()->back();
            }
        } else {
            toast()->error(Message::illegalError, Lang::get('cms.error'));
            return back();
        }
    }

    public function avatar(Request $request)
    {
        $folderPath = public_path('upload/avatars/');

        $image_parts = explode(";base64,", $request->image);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);

        $imageName = uniqid() . '.png';

        $imageFullPath = $folderPath . $imageName;
        file_put_contents($imageFullPath, $image_base64);

        Image::updateOrCreate([
            'user_id' => Auth::id(),
            'imageable_type' =>"user",
            'imageable_id' => Auth::id(),
        ], [
            'url' => "/upload/avatars/" . $imageName,
            'user_id' => Auth::id(),
            'imageable_type' =>"user",
            'imageable_id' => Auth::id(),
        ]);

        return response()->json(['success' => 'تصویر با موفقیت بروز گردید']);
    }


    // ===================================================  address


    public function addressStore(Request $request)
    {
        $name = $request->input('name');
        $userID = $this->user->id;
        $mobile = $request->input('mobile');
        $tell = $request->input('tell');
        $province_id = $request->input('province_id');
        $city_id = $request->input('city_id');
        $fullAddress = $request->input('fullAddress');
        $postal_code = $request->input('postal_code');

        $this->validate($request, [
            'name' => 'required',
            'mobile' => 'required',
            'tell' => 'required',
            'province_id' => 'required',
            'city_id' => 'required',
            'fullAddress' => 'required',
            'postal_code' => 'required',
        ]);

        $requestData = [
            'user_id' => $userID,
            'name' => $name,
            'mobile' => $mobile,
            'tell' => $tell,
            'fullAddress' => $fullAddress,
            'province_id' => $province_id,
            'city_id' => $city_id,
            'postal_code' => $postal_code
        ];

        DB:: beginTransaction();
        $createAddress = Address::create($requestData);
        if ($createAddress instanceof Address) {
            DB::commit();
            toast()->success( Lang::get('cms.success'),Message::successMessageCreate);
            return redirect()->route('panel.profile.index');
        } else {
            DB::rollBack();
            toast()->error(Message::errorMessageCreate, Lang::get('cms.error'));
            return redirect()->route('panel.profile.index');
        }


    }

    public function addressEdit($id)
    {
        if (is_numeric($id)) {
            $title = "ویرایش آدرس";
            SEO::setTitle('ویرایش آدرس');
            $province_id = Province::select('id', 'name')->get();
            $findAddress = Address::owner()->findOrFail($id);
            return view('panel.profile.create', compact('title', 'findAddress', 'province_id'));

        } else {
            toast()->error(Message::illegalError, Lang::get('cms.error'));
            return back();
        }
    }

    public function addressUpdate(Request $request, $id)
    {
        if (is_numeric($id)) {
            $userID = $this->user->id;
            $findAddress = Address::owner()->findOrFail($id);
            $name = $request->input('name');
            $mobile = $request->input('mobile');
            $tell = $request->input('tell');
            $province_id = $request->input('province_id');
            $city_id = $request->input('city_id');
            $fullAddress = $request->input('fullAddress');
            $postal_code = $request->input('postal_code');

            $this->validate($request, [
                'name' => 'required',
                'mobile' => 'required',
                'tell' => 'required',
                'province_id' => 'required',
                'city_id' => 'required',
                'fullAddress' => 'required',
                'postal_code' => 'required|iran_postal_code',
            ]);

            $requestData = [
                'user_id' => $userID,
                'name' => $name,
                'mobile' => $mobile,
                'tell' => $tell,
                'fullAddress' => $fullAddress,
                'province_id' => $province_id,
                'city_id' => $city_id,
                'postal_code' => $postal_code
            ];

            DB::beginTransaction();
            $update = $findAddress->update($requestData);
            if ($update) {
                DB::commit();
                toast()->success(Message::successMessageEdit, Lang::get('cms.success'));
                return redirect()->route('panel.profile.address');
            } else {
                DB::rollBack();
                toast()->success(Message::errorMessageEdit, Lang::get('cms.error'));
                return redirect()->route('panel.profile.address');
            }

        } else {
            toast()->error(Message::illegalError, Lang::get('cms.error'));
            return back();
        }
    }

    public function addressDelete($id)
    {
        if (is_numeric($id)) {
            $findAddress = Address::owner()->findOrFail($id);
            DB::beginTransaction();
            $delete = $findAddress->delete();
            if ($delete) {
                DB::commit();
                toast()->success(Message::successMessageDelete, Lang::get('cms.success'));
                return back();
            } else {
                DB::rollBack();
                toast()->error(Message::errorMessageDelete, Lang::get('cms.error'));
                return back();
            }

        } else {
            toast()->error(Message::illegalError, Lang::get('cms.error'));
            return back();
        }
    }

    public function ajaxGetCity(Request $request)
    {
        $provinceID = $request->input('provinceID');
        $allCity = City::where('province_id', $provinceID)->get();
        if (isset($allCity) && !empty($allCity) && count($allCity) > 0) {
            $view = view('panel.profile.ajax.city', compact('allCity'))->render();
            return response()->json(['html' => $view]);
        } else {
            return [
                'status' => 100,
                'message' => 'شهر مورد نظر یافت نشد لطفا استان خود را به درستی انتخاب نمایید.'
            ];
        }
    }

}
