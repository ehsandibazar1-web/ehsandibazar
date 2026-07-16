@extends('emails.master')

@section('email-name')
    {{ $user->name . " " . $user->family }}
@endsection
@section('email-content')
    <div style="font-family: Tahoma;background-color: #d4d4d4;padding: 15px;border-radius: 5px;margin-top: 50px;margin-bottom: 50px">
        <h5 style="text-align: right;font-family: Tahoma;color: black; margin-top: 5px;">
            لینک فعالسازی
        </h5>
        <hr>
        <p style="font-family: Tahoma, sans-serif; box-sizing: border-box; color: #74787E; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: right;">
            کاربر گرامی , از طریق لینک زیر می توانید حساب کاربری خود را فعال کنید
        </p>
        <br>
        <span style="font-family: Tahoma;float: left"> با تشکر </span>
        <br>
        <br>
        <br>
        <a type="submit" style="text-decoration: none;;border: none;padding: 11px 20px;border-radius: 5px;background-color: #343d5e;color: white;font-family: Tahoma !important;font-size: 12px;line-height: 21px;cursor: pointer;" href="{{route('site.activation' ,['token' =>  $code])}}">لینک فعال سازی</a>
    </div>
@endsection


