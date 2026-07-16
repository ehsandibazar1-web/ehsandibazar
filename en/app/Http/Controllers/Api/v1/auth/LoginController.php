<?php

namespace App\Http\Controllers\Api\v1\auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function Login(Request $request)
    {
        // Validation Data
        $validData = $this->validate($request, [
            'mobile' => 'required|exists:users',
            'password' => 'required'
        ]);

        // Check Login User
        if(! auth()->attempt($validData)) {
            
             return response([
            'status' => 100,
            'message' => 'The information is incorrect'
        ]);
        
          
        }

        // auth()->user()->update([
        //     'api_token' => Str::random(120)
        // ]);

        return response([
            'status' => 200,
            'data' => [
                'data' => auth()->user(),
                'api-token' => auth()->user()->api_token
            ],
            'message' => 'success',
        ]);
    }
}
