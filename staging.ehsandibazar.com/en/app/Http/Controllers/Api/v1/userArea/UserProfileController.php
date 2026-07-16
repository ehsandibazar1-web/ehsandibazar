<?php

namespace App\Http\Controllers\Api\v1\userArea;

use App\Model\Address;
use App\Model\City;
use App\Model\Province;
use App\Services\ImageServices\ImageServices;
use App\User;
use App\Utility\Message;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserProfileController extends Controller
{


    public function index()
    {
        $profile = User::where('id', \auth()->user()->id)->first();

        return response([
            'status' => 200,
            'data' => [
                'profile' => $profile,
            ],
            'message' => 'success',
        ]);
    }

    public function AddressShow()
    {
        $id = \auth()->user()->id;
        $profile = User::with(['address' => function($q){
            $q->with(['province','city']);
        }])->find($id);
        $province = Province::all();
        return response([
            'status' => 200,
            'data' => [
                'profile' => $profile,
                'province' => $province
            ],
            'message' => 'success',
        ]);
    }

    public function edit($id)
    {
        if (auth()->user()->id == \auth()->user()->id) {
            $profile = User::with('image')->where('id', \auth()->user()->id)->first();
            return response([
                'status' => 200,
                'data' => [
                    'profile' => $profile,
                ],
                'message' => 'success',
            ]);
        } else {
            return response([
                'status' => 102,
                'message' => 'back',
            ]);
        }

    }

    public function ProfileUpdate(Request $request)
    {


            /* seller information */
            $postalCode = $request->input('postal_code');
            $nationalCode = $request->input('national_code');
            $store_name = $request->input('store_name');
            $sheba_number = $request->input('sheba_number');
            $account_number = $request->input('account_number');
            $cart_number = $request->input('cart_number');
            /* seller information */

            /* image */
            $image = $request->input('filepath');
            /* image */

            /* public information */
            $name = $request->input('name');
            $family = $request->input('family');
            $tell = $request->input('tell');
            $mobile = $request->input('mobile');
            $email = $request->input('email');
            /* public information */

            $find = User::where('id', \auth()->user()->id)->firstOrfail();
            if ($image && !empty($image)) {
                $updateImage = ImageServices::update_images($find, $request, auth()->user()->id);
                if ($updateImage <= 0) {
                    ImageServices::create_images($find, $request, auth()->user()->id);
                }
            } else {
                ImageServices::delete_images($find);
            }

            $this->validate($request, [
                'name' => 'required',
                'family' => 'required',
                'tell' => 'required'
            ]);


            $saveData = [
                'name' => $name,
                'family' => $family,
                'tell' => $tell,
                'email' => $email,
            ];


            $update = User::find(\auth()->user()->id);

            if ($update->update($saveData)) {
                return response([
                    'status' => 200,
                    'message' => Message::successMessageEdit,
                ]);
            } else {
                return response([
                    'status' => 102,
                    'message' => Message::errorMessageEdit,
                ]);
            }

    }

    public function ChangePw(Request $request)
    {

        if (!(Hash::check($request->get('current_password'), Auth::user()->password))) {
            // The passwords matches
            return response([
                'status' => 102,
                'message' => 'Your current password does not matches with the password you provided. Please try again.',
            ]);
        }
        if (strcmp($request->get('current_password'), $request->get('new_password')) == 0) {
            //Current password and new password are same
            return response([
                'status' => 102,
                'message' => 'New Password cannot be same as your current password. Please choose a different password.',
            ]);

        }
        $validatedData = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed',
        ]);
        //Change Password
        $user = Auth::user();
        $user->password = bcrypt($request->get('new_password'));
        $user->save();
        return response([
            'status' => 200,
            'message' => 'Password changed successfully !',
        ]);

    }

    public function getCityList(Request $request)
    {
        $city = City::where('province_id', '=', $request->province_id)->get();
        return response()->json($city);
    }

    public function Address(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'province_id' => 'required',
            'city_id' => 'required',
            'fullAddress' => 'required',
            'name' => 'required',
            'tell' => 'required',
            'mobile' => 'required',
            'postal_code' => 'required'
        ]);

        if ($validator->fails()) {
            return response([
                'status' => 300,
                'error' => $validator->errors()->all(),
                'message' => 'validation error',
            ]);
        }

        auth()->user()->address()->create($request->all());
        return response([
            'status' => 200,
            'message' => Message::successMessageCreate,
        ]);

    }

    public function EditAddress(Request $request, $id)
    {

        $this->validate($request, [
            'province_id' => 'required',
            'city_id' => 'required',
            'name' => 'required',
            'mobile' => 'required',
        ]);
        $update = Address::find($id);
        if ($update->update($request->all())) {
            return response([
                'status' => 200,
                'message' => Message::successMessageEdit,
            ]);
        } else {
            return response([
                'status' => 102,
                'message' => Message::errorMessageEdit,
            ]);
        }
    }

    public function DeleteAddress(Request $request)
    {
        $id = $request->input('id');
        $address = Address::find($id)->delete();
        return response([
            'status' => 200,
            'message' => Message::successMessageDelete,
        ]);

    }

    public function ajaxCity(Request $request)
    {
        $province = $request->input('provinceId');
        if ($province) {
            $city = City::where('province_id', $province)->get();
            return response([
                'status' => 200,
                'data' => $city,
                'message' => 'success'
            ]);
        }

    }
}
