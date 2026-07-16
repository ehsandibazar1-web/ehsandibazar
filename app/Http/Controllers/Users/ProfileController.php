<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Site\SiteController;
use App\Model\City;
use App\Http\Controllers\Controller;
use App\Model\File;
use App\Model\Image;
use App\Model\Systeminfmanage;
use App\Services\ImageServices\ImageServices;
use App\User;
use App\Utility\getUser;
use App\Utility\Message;
use App\Utility\Status;
use Carbon\Carbon;
use foo\bar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Session;
use App\Model\Address;
use SEO;


class ProfileController extends Controller
{
    public function index()
    {
        SEO::setTitle('پروفایل');
        $profile = Auth::user();
        $genders = Systeminfmanage::whereStatus(Status::active)->where('systeminf_id', 13)->latest()->get();
        $degreeEducation = Systeminfmanage::whereStatus(Status::active)->where('systeminf_id', 14)->get();
        $interests = Systeminfmanage::whereStatus(Status::active)->where('systeminf_id', 15)->get();
        $maritalStatus = Systeminfmanage::whereStatus(Status::active)->where('systeminf_id', 16)->get();
        $languages = Systeminfmanage::whereStatus(Status::active)->where('systeminf_id', 17)->get();
        $cigarettes = Systeminfmanage::whereStatus(Status::active)->where('systeminf_id', 18)->get();
        $drink = Systeminfmanage::whereStatus(Status::active)->where('systeminf_id', 19)->get();
        $countries = Country::whereStatus(Status::active)->get();
        $posts = $profile->posts;
        return view('users.profile.index', compact('profile', 'posts', 'countries', 'genders', 'degreeEducation', 'interests', 'maritalStatus', 'languages', 'cigarettes', 'drink'));
    }

    public function update(Request $request)
    {
        $id = Auth::user()->id;
        $this->validate($request, [
            'name' => 'required',
            'family' => 'required',
            'mobile' => ['numeric'],
            'tell' => ['numeric'],
            'email' => 'required',
        ]);

        if (is_numeric($id)) {



            /* public information */
            $name = $request->input('name');
            $family = $request->input('family');
            $email = $request->input('email');
            $tell = $request->input('tell');



            $find = Auth::user();


            $saveData = [
                'name' => $name,
                'family' => $family,
                'tell' => $tell,
                'email' => $email,
            ];


            if (Auth::user()->update($saveData)) {
                alert()->success('موفقیت آمیز :)', 'اطلاعات پروفایل شما بروز شد ، ')->showConfirmButton('بستن');
                return redirect()->back();
            } else {
                alert()->error('خطایی رخ داده!', 'متاسفیم :(');
                return redirect()->back();
            }
        } else {
            alert()->error('خطایی رخ داده!', 'متاسفیم');
            return back();
        }
    }

    public function ChangePw(Request $request)
    {

        if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
            // The passwords matches
            alert()->error('متاسفیم!', 'رمز عبور فعلی شما با رمز ورود ارائه شده مطابقت ندارد. لطفا دوباره تلاش کنید.');
            return redirect()->back();
        }
        if (strcmp($request->get('current-password'), $request->get('new-password')) == 0) {
            //Current password and new password are same
            alert()->error('متاسفیم!', 'رمز ورود جدید مشابه رمز ورود فعلی شما است. لطفاً رمزعبور دیگری انتخاب کنید');
            return redirect()->back();
        }
        $validatedData = $request->validate([
            'current-password' => 'required|max:100',
            'new-password' => 'required|string|min:6|confirmed|max:100',
        ]);
        //Change Password
        $user = Auth::user();
        $user->password = bcrypt($request->get('new-password'));
        $user->save();
        alert()->success('موفق', 'رمز عبور با موفقیت تغییر کرد');
        Auth::logoutOtherDevices($request->get('new-password'));
        return redirect()->back();
    }

    public function ckUpload(Request $request)
    {
        $validator = Validator::make($request->all(), ['upload' => ['required', 'mimes:jpg,gif,png,jpeg', 'max:3072']]);
        if (isset($validator) && $validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }

        if ($request->hasFile('upload')) {
            $path = "ckuploader/" . Auth::id();
            $file = $request->file('upload')->store($path);
            return response([
                "uploaded" => true,
                "url" => env('APP_URL') . "/storage/" . $file
            ]);
        }
        return response([
            "uploaded" => false,
            "error" => ["message" => "could not upload this image"]
        ]);
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
            'imageable_type' => get_class(Auth::user()),
            'imageable_id' => Auth::id(),
        ], [
            'url' => "/upload/avatars/" . $imageName,
            'user_id' => Auth::id(),
            'imageable_type' => get_class(Auth::user()),
            'imageable_id' => Auth::id(),
        ]);

        return response()->json(['success' => 'تصویر با موفقیت بروز گردید']);
    }


}
