@extends('emails.master')

@section('email-name')
    {{ $users->name . " " . $users->family }}
@endsection
@section('email-content')
    <div style="font-family: Tahoma;background-color: #d4d4d4;padding: 15px;border-radius: 5px;margin-top: 50px;margin-bottom: 50px">
        <h5 style="text-align: right;font-family: Tahoma;color: black; margin-top: 5px;">
            هشدار!
        </h5>
        <hr>
        <p style="font-family: Tahoma, sans-serif; box-sizing: border-box; color: #74787E; font-size: 16px; line-height: 1.5em; margin-top: 0; text-align: right;">
           کاربر گرامی , متن سوال
        </p>
        <a href="">لینک سایت</a>
    </div>
@endsection
