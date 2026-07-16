@extends('emails.master')


@section('email-name')
    {{ $user->name . " " . $user->family }}
@endsection
@section('email-content')
    <div style="font-family: Tahoma;background-color: #d4d4d4;padding: 15px;border-radius: 5px;margin-top: 50px;margin-bottom: 50px">
        <h5 style="text-align: right;font-family: Tahoma;color: black; margin-top: 5px;">
            {{$event->title}}
        </h5>
        <hr>
        <p style="text-align: right;font-family: Tahoma;">{!! $event->body !!}</p>
    </div>
@endsection