@extends('site.layout.master')

@section('content')

    <div class="bg-gray wrapper default">
        <div class="main-page container-fluid">
            <div class="container page-wrapper">
                <div class="page">
                    <h1 class="title-page">{{ $page->title }}</h1>
                    <div class="container">
                        <div class="content-page">
                            {!! $page->body !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
