<?php

namespace App\Http\Controllers\Api\v1\auth;

use App\User;
use App\Utility\Level;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class RegisterController extends Controller
{
    public function Register(Request $request)
    {
        $validData = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:190'],
            'family' => ['string', 'max:190'],
            'mobile' => [
                'required',
                'numeric',
                'regex:/0{1}9{1}[0-9]{9}/',
                Rule::unique('users', 'mobile')->where(function ($query) {
                    $query->where('deleted_at', null);
                }),
            ],
            'password' => ['required', 'string', 'min:6', 'max:100', 'confirmed'],
        ]);

        if ($validData->fails()) {
            return response([
                'status' => 300,
                'error' => $validData->errors()->all(),
                'message' => 'validation error',
            ]);
        }

        $requestData = [
            'name' => $request->input('name'),
            'mobile' => $request->input('mobile'),
            'level' => Level::USER,
            'password' => Hash::make($request->input('password')),
            'api_token' => Str::random(120),
        ];
        $user = User::create($requestData);
        $user->roles()->sync(2);

        return response([
            'status' => 200,
            'data' => [
                'data' => $user,
                'api-token' => $user->api_token
            ],
            'message' => 'success',
        ]);
    }
}
