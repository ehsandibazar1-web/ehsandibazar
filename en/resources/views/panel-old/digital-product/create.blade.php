@extends('panel-old.layout.master')


@section('admin-css')
    <link rel="stylesheet" href="{{url('admin_theme/css/selected.css')}}"/>
    <link href="{{ url('admin_theme/css/select2.css') }}" rel="stylesheet"/>
@endsection

@section('admin-js')
    <script src="{{ url('admin_theme/js/select2.js') }}"></script>
    <script src="{{ url('admin_theme/js/selected.js')}}"></script>
    <script>
        $(document).ready(function () {
            $('.select2').select2();
        });

    </script>
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                   افزودن محصول دیجیتال برای کاربر
                    @include('generals.allErrors')
                    @include('generals.sessionMessage')
                </header>

                <div class="panel-body">
                    <div class=" form">
                        <form class="cmxform form-horizontal tasi-form"  id="commentForm" method="post" action="{{ route('panel.digitalProduct.store')}}" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group ">
                                <label for="cname" class="control-label col-lg-2">کاربران</label>
                                <div class="col-lg-10">
                                    <select class="form-control select-option select2" name="user">
                                        @foreach($users as $user)
                                            <option value="{{ $user->id  }}">{{ $user->name." ".$user->family."-".$user->mobile  }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group ">
                                <label for="cname" class="control-label col-lg-2">محصولات</label>
                                <div class="col-lg-10">
                                    <select class="form-control select-option select2" name="product[]"  multiple="multiple">
                                        @foreach($products as $product)
                                            <option value="{{ $product->id  }}">{{ $product->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-lg-offset-2 col-lg-10">
                                    <input class="btn btn-success pull-left" type="submit" value="ارسال">

                                </div>
                            </div>
                            <br><br><br><br><br>
                        </form>
                    </div>

                </div>

            </section>
        </div>
    </div>

@endsection
