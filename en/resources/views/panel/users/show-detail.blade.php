@extends('panel.layout.master')

@section('top-menu')
    @include('panel.layout.partials.topNav')
@stop

@section('right-menu')
    @include('panel.layout.partials.rightNav')
@stop
@section('admin-css')
    <link href="{{ asset('admin/assets/js/bundles/lightgallery/dist/css/lightgallery.css')  }}" rel="stylesheet">
    <style>
        .lg-outer {
            direction: ltr !important;
        }

        .select-wrapper {
            display: none;
        }
    </style>
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">

@endsection

@section('admin-js')
    <script src="{{ asset('admin/assets/js/bundles/lightgallery/dist/js/lightgallery-all.js') }}"></script>
    <!-- Custom Js -->
    <script src="{{ asset('admin/assets/js/pages/medias/image-gallery.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
    <script>
        $('.selectpicker').selectpicker();
    </script>
@endsection
@section('content')

    <!-- Basic Examples -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        {{ isset($title) ? $title : "" }}
                    </h2>

                    <div class="pull-left">
                        @can('panel.role.index')
                            <a href="{{ route('panel.role.index')  }}"
                               class="btn btn-outline-default btn-border-radius"> لیست نقش کاربری </a>
                        @endcan
                        @can('panel.permission.index')
                            <a href="{{ route('panel.permission.index')  }}"
                               class="btn btn-outline-default btn-border-radius">لیست سطوح دسترسی </a>
                        @endcan

                        @can('panel.users.create')
                            <a href="{{ route('panel.users.create')  }}"
                               class="btn btn-outline-default btn-border-radius"> ایجاد کاربر جدید </a>
                        @endcan
                        @can('panel.LevelManage.index')
                            <a href="{{ route('panel.LevelManage.index')  }}"
                               class="btn btn-outline-warning btn-border-radius">مدیریت نقش کاربران</a>
                        @endcan

                    </div>

                </div>
                <div class="body">
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="card">
                                <div class="header">
                                    <h2>
                                        اطلاعات پروفایل <strong> {{ $user->fullName }}</strong></h2>
                                    <ul class="header-dropdown m-r--5">
                                        <li class="dropdown">
                                            <a href="#" onclick="return false;" class="dropdown-toggle"
                                               data-toggle="dropdown" role="button" aria-haspopup="true"
                                               aria-expanded="false">
                                                <i class="material-icons">more_vert</i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="body">
                                    <!-- Nav tabs -->
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li role="presentation">
                                            <a href="#home_with_icon_title" data-toggle="tab" class="show active">
                                                <i class="material-icons">error_outline</i> عمومی
                                            </a>
                                        </li>
                                        <li role="presentation">
                                            <a href="#profile_with_icon_title" data-toggle="tab" class="">
                                                <i class="material-icons">collections</i> گالری تصاویر
                                            </a>
                                        </li>
                                        <li role="presentation">
                                            <a href="#messages_with_icon_title" data-toggle="tab" class="">
                                                <i class="material-icons">video_library</i> ویدیو
                                            </a>
                                        </li>
                                        <li role="presentation">
                                            <a href="#settings_with_icon_title" data-toggle="tab" class="">
                                                <i class="material-icons">near_me</i>پست ها
                                            </a>
                                        </li>
{{--                                        <li role="presentation">--}}
{{--                                            <a href="#statusProfile" data-toggle="tab" class="">--}}
{{--                                                <i class="material-icons">done</i>تایید اطلاعات پروفایل--}}
{{--                                            </a>--}}
{{--                                        </li>--}}
                                    </ul>
                                    <!-- Tab panes -->
                                    <div class="tab-content">
                                        <div role="tabpanel" class="tab-pane fade active show"
                                             id="home_with_icon_title">
                                            <b>اطلاعات پروفایل</b>
                                            @include('generals.allErrors')
                                            <form class="form-horizontal" method="post"
                                                  action="{{ route('panel.users.detailUpdate',$user) }}"
                                                  enctype="multipart/form-data">
                                                @csrf

                                                <div class="row">
                                                    <div class="col-md-6 col-12">
                                                        <label for="inputName"
                                                               class="col-sm-3 col-form-label">نام</label>
                                                        <input type="text" class="form-control" id="inputName"
                                                               placeholder="نام" name="name"
                                                               value="{{ isset($user) ? $user->name : old('name') }}">
                                                    </div>
                                                    <div class="col-md-6 col-12">
                                                        <label for="inputName" class="col-sm-3 col-form-label">نام
                                                            خانوادگی</label>
                                                        <input type="text" class="form-control" id="Family"
                                                               placeholder="نام خانوادگی"
                                                               name="family"
                                                               value="{{ isset($user) ? $user->family : old('family') }}">
                                                    </div>
                                                    <div class="col-md-6 col-12">
                                                        <label for="inputEmail"
                                                               class="col-sm-3 col-form-label">ایمیل</label>
                                                        <input type="email" class="form-control" id="inputEmail"
                                                               placeholder="ایمیل" name="email"
                                                               value="{{ isset($user) ? $user->email : old('email') }}">
                                                    </div>
                                                    <div class="col-md-6 col-12">
                                                        <label for="inputName2"
                                                               class="col-sm-3 col-form-label">موبایل</label>
                                                        <input type="tel" class="form-control" id="inputName2"
                                                               placeholder="موبایل" name="mobile"
                                                               value="{{ isset($user) ? $user->mobile : old('mobile') }}">
                                                    </div>

                                                    <div class="col-md-6 col-12">
                                                        <label for="slug" class="col-sm-3 col-form-label">آدرس پروفایل</label>
                                                        <input type="text" class="form-control" id="slug"
                                                               placeholder="نام کاربری پروفایل..." name="slug"
                                                               value="{{ isset($user) ? $user->slug : old('slug') }}">
                                                    </div>

                                                    <div class="col-md-6 col-12">
                                                        <label for="profileType" class="col-sm-4 col-form-label">نوع
                                                            پیج</label>

                                                        <select name="profileType" class="selectpicker"
                                                                id="profileType">
                                                            @foreach(\App\Utility\ProfileType::getTypeIcon() as $key => $item)
                                                                <option value="{{$key}}"
                                                                        data-content="{{ $item }}" {{ isset($user, $user->detail) && $key== $user->detail->page_type ? "selected" :null }}></option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="col-md-6 col-12">
                                                        <label for="birth_date" class="col-sm-3 col-form-label">تاریخ
                                                            تولد</label>
                                                        <input type="date" class="form-control" id="birth_date"
                                                               name="birth_date"
                                                               value="{{ isset($user->detail) && !empty($user->detail) ? $user->detail->birth_date : old('birth_date') }}">
                                                    </div>

                                                    <div class="col-md-6 col-12">
                                                        <label for="place_of_birth" class="col-sm-3 col-form-label">محل
                                                            تولد</label>
                                                        <input type="text" class="form-control" id="place_of_birth"
                                                               name="place_of_birth"
                                                               value="{{ isset($user->detail) && !empty($user->detail) ? $user->detail->place_of_birth : old('place_of_birth') }}">
                                                    </div>

                                                    <div class="col-md-6 col-12">
                                                        <label for="location" class="col-sm-3 col-form-label">محل
                                                            زندگی</label>
                                                        <input type="text" class="form-control" id="location"
                                                               name="location"
                                                               value="{{ isset($user->detail) && !empty($user->detail) ? $user->detail->location : old('location') }}">
                                                    </div>

                                                    <div class="col-md-6 col-12">
                                                        <label for="education_place" class="col-sm-3 col-form-label">
                                                            محل
                                                            تحصیل</label>
                                                        <input type="text" class="form-control" id="education_place"
                                                               name="education_place"
                                                               value="{{ isset($user->detail) && !empty($user->detail) ? $user->detail->education_place : old('education_place') }}">
                                                    </div>


                                                    @if(isset($drink) && !empty($drink))
                                                        <div class="col-md-6 col-12">
                                                            <label for="countries"
                                                                   class="col-sm-4 col-form-label">کشور</label>
                                                            <select name="countries[]" class="select2">
                                                                <option>انتخاب نمایید...</option>
                                                                @if(isset($countries))
                                                                    @foreach($countries as $item)
                                                                        <option
                                                                            value="{{$item->id}}" {{ isset($user) && in_array($item->id , $user->countries->pluck('id')->toArray()) ? "selected" :null }} >{{ $item->fa_name." - ".$item->en_name}}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                    @endif

                                                    @if(isset($genders) && !empty($genders))
                                                        <div class="col-md-6 col-12">
                                                            <label for="gender"
                                                                   class="col-sm-3 col-form-label">جنسیت</label>
                                                            <select name="gender">
                                                                <option>انتخاب نمایید...</option>
                                                                @foreach($genders as $gender)
                                                                    <option
                                                                        value="{{$gender->id}}" {{ isset($user->detail) && !empty($user->detail) && $user->detail->gender == $gender->id ? 'selected' : null }}>{{$gender->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    @endif

                                                    @if(isset($degreeEducation) && !empty($degreeEducation))
                                                        <div class="col-md-6 col-12">
                                                            <label for="degree_education"
                                                                   class="col-sm-3 col-form-label">مدرک
                                                                تحصیلی</label>
                                                            <select name="degree_education">
                                                                <option>انتخاب نمایید...</option>
                                                                @foreach($degreeEducation as $item)
                                                                    <option
                                                                        value="{{$item->id}}" {{ isset($user->detail) && !empty($user->detail) && $user->detail->degree_education == $item->id ? 'selected' : null }}>{{$item->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    @endif

                                                    @if(isset($interests) && !empty($interests))
                                                        <div class="col-md-6 col-12">
                                                            <label for="degree_education"
                                                                   class="col-sm-3 col-form-label">علایق</label>
                                                            <select name="interests">
                                                                <option>انتخاب نمایید...</option>
                                                                @foreach($interests as $item)
                                                                    <option
                                                                        value="{{$item->id}}" {{ isset($user->detail) && !empty($user->detail) && $user->detail->interests == $item->id ? 'selected' : null }}>{{$item->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    @endif


                                                    @if(isset($maritalStatus) && !empty($maritalStatus))
                                                        <div class="col-md-6 col-12">
                                                            <label for="degree_education"
                                                                   class="col-sm-3 col-form-label">وضعیت
                                                                تاهل</label>
                                                            <select name="marital_status">
                                                                <option>انتخاب نمایید...</option>
                                                                @foreach($maritalStatus as $item)
                                                                    <option
                                                                        value="{{$item->id}}" {{ isset($user->detail) && !empty($user->detail) && $user->detail->marital_status == $item->id ? 'selected' : null }}>{{$item->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    @endif

                                                    @if(isset($languages) && !empty($languages))
                                                        <div class="col-md-6 col-12">
                                                            <label for="degree_education"
                                                                   class="col-sm-3 col-form-label">زبان</label>
                                                            <select name="language">
                                                                <option>انتخاب نمایید...</option>
                                                                @foreach($languages as $item)
                                                                    <option
                                                                        value="{{$item->id}}" {{ isset($user->detail) && !empty($user->detail) && $user->detail->language == $item->id ? 'selected' : null }}>{{$item->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    @endif

                                                    <div class="col-md-6 col-12">
                                                        <label for="height" class="col-sm-3 col-form-label">قد</label>
                                                        <select name="height">
                                                            <option>انتخاب نمایید...</option>
                                                            @for($i=150;$i<=210;$i++)
                                                                <option
                                                                    value="{{$i}}" {{ isset($user->detail) && !empty($user->detail) && $user->detail->height == $i ? 'selected' : null }}>{{$i}}</option>
                                                            @endfor
                                                        </select>

                                                    </div>


                                                    @if(isset($cigarettes) && !empty($cigarettes))
                                                        <div class="col-md-6 col-12">
                                                            <label for="cigarettes" class="col-sm-3 col-form-label">سیگاری
                                                                هستید؟</label>
                                                            <select name="cigarettes">
                                                                <option>انتخاب نمایید...</option>
                                                                @foreach($cigarettes as $item)
                                                                    <option
                                                                        value="{{$item->id}}" {{ isset($user->detail) && !empty($user->detail) && $user->detail->cigarettes == $item->id ? 'selected' : null }}>{{$item->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    @endif

                                                    @if(isset($drink) && !empty($drink))
                                                        <div class="col-md-6 col-12">
                                                            <label for="drink" class="col-sm-4 col-form-label">تمایل به
                                                                مصرف
                                                                نوشیدنی</label>
                                                            <select name="drink">
                                                                <option>انتخاب نمایید...</option>
                                                                @foreach($drink as $item)
                                                                    <option
                                                                        value="{{$item->id}}" {{ isset($user->detail) && !empty($user->detail) && $user->detail->drink == $item->id ? 'selected' : null }}>{{$item->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    @endif

                                                    <div class="col-md-6 col-12">
                                                        <label for="instagram" class="col-sm-3 col-form-label">اینستاگرام</label>
                                                        <input type="text" class="form-control" id="instagram"
                                                               placeholder="آیدی اینستاگرام ، مثال : gapgah"
                                                               name="instagram"
                                                               value="{{ isset($user->detail) && !empty($user->detail) ? $user->detail->instagram : old('instagram') }}">
                                                    </div>

                                                    <div class="col-md-6 col-12">
                                                        <label for="facebook"
                                                               class="col-sm-3 col-form-label">فیسبوک</label>
                                                        <input type="text" class="form-control" id="facebook"
                                                               placeholder="آیدی فیسبوک ، مثال : gapgah"
                                                               name="facebook"
                                                               value="{{ isset($user->detail) && !empty($user->detail) ? $user->detail->facebook : old('facebook') }}">
                                                    </div>
                                                    <div class="col-md-12 col-12">
                                                        <label for="slogan" class="col-sm-3 col-form-label">شعار</label>
                                                        <input type="text" class="form-control" id="slogan"
                                                               placeholder="شعار..."
                                                               name="slogan"
                                                               value="{{ isset($user->detail) && !empty($user->detail) ? $user->detail->slogan : old('slogan') }}">
                                                    </div>

                                                    <div class="col-md-12 col-12">
                                                        <label for="bio"
                                                               class="col-sm-3 col-form-label">بیوگرافی</label>
                                                        <input type="text" class="form-control" id="bio"
                                                               placeholder="بیوگرافی..."
                                                               name="bio"
                                                               value="{{ isset($user->detail) && !empty($user->detail) ? $user->detail->bio : old('bio') }}">
                                                    </div>
                                                </div>


                                                <div class="col-md-6 col-12">
                                                    <label for="avatar" class="col-sm-3 col-form-label">آواتار</label>
                                                    <input type="file" name="avatar" class="form-control-file">
                                                    @if(isset($user->image[0]) && !empty($user->image[0]))
                                                        <a target="_blank" href="{{$user->image[0]->url}}"><img
                                                                src="{{$user->image[0]->url}}" width="100"
                                                                title="برای بزرگ نمایی کلیک نمایید"></a>
                                                    @else
                                                        تصویری ندارد
                                                    @endif
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <label for="cover" class="col-sm-3 col-form-label">تصویر
                                                        کاور</label>
                                                    <input type="file" name="cover" class="form-control-file">
                                                    @if(isset($user->detail->cover) && !empty($user->detail->cover))
                                                        <a target="_blank"
                                                           href="{{ env('APP_URL').$user->detail->cover }}"><img
                                                                src="{{env('APP_URL').$user->detail->cover}}"
                                                                width="100" title="برای بزرگ نمایی کلیک نمایید"></a>
                                                    @else
                                                        تصویری ندارد
                                                    @endif
                                                </div>

                                                <div class="col-md-6 col-12 mt-3">
                                                    <button type="submit" class="btn btn-danger pull-left">ویرایش
                                                        پروفایل
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                        <div role="tabpanel" class="tab-pane fade" id="profile_with_icon_title">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="card">
                                                        <div class="header">
                                                            <h2>
                                                                <strong>گالری تصاویر</strong>
                                                            </h2>

                                                        </div>
                                                        <div class="body">
                                                            <div id="aniimated-thumbnials"
                                                                 class="list-unstyled row clearfix">
                                                                @if(isset($user->detail->image) && count($user->detail->image) > 0)
                                                                    @foreach($user->detail->image as $item)
                                                                        <div
                                                                            class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                                                                            <a href="{{ $item->url }}"
                                                                               data-sub-html="تصاویر {{ $user->fullName }}">
                                                                                <img class="img-responsive thumbnail"
                                                                                     src="{{ $item->url }}"
                                                                                     alt="">
                                                                            </a>
                                                                            @can('panel.users.deleteGallery')
                                                                                <a target="_blank"
                                                                                   href="{{route('panel.users.deleteGallery',$item->id)}}"
                                                                                   class="remove-gallery"><i
                                                                                        class="fas fa-trash-alt"></i></a>
                                                                            @endcan
                                                                        </div>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div role="tabpanel" class="tab-pane fade" id="messages_with_icon_title">
                                            <b>ویدیو</b>
                                            @if(isset($user->detail->files[0]) && !empty($user->detail->files[0]))
                                                <li>
                                                    <video id="my-video" class="video-js" controls="" preload="auto"
                                                           loop="">
                                                        <source
                                                            src="{{ $user->detail->files[0]->url }}"
                                                            type="video/mp4">
                                                    </video>
                                                </li>
                                            @else
                                                <li>
                                                    ویدیویی آپلود نشده...
                                                </li>
                                            @endif
                                        </div>
                                        <div role="tabpanel" class="tab-pane fade" id="settings_with_icon_title">
                                            <b>پست ها</b>
                                            @if(isset($user->posts) && !empty($user->posts) && count($user->posts) > 0)
                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                                    <div class="card">
                                                        <div class="body">
                                                            <div class="review-block">
                                                                @foreach($user->posts as $post)
                                                                    <div class="row">
                                                                        <div class="review-img">
                                                                            <img class="img-circle user-img-circle"
                                                                                 src="{{ $post->user->image[0]->url }}"
                                                                                 alt="{{ $post->user->fullName }}">
                                                                        </div>
                                                                        <div class="col">
                                                                            <h6 class="m-b-15"> {{ $post->user->fullName }}
                                                                                @php
                                                                                    \App\Models\Post::$preventAttrSet = false
                                                                                @endphp
                                                                                <span
                                                                                    class="float-right m-r-10 text-muted">&nbsp;{{ $post->created_at }}&nbsp;</span>
                                                                            </h6>

                                                                            <p class="m-t-15 m-b-15 text-muted">
                                                                                {!! $post->description !!}
                                                                            </p>

                                                                            <a @can('panel.post.status') href="{{ route('panel.post.status',['id' => $post->id])  }}" @endcan>
                                                                                {{ \App\Utility\Status::getStatusJob($post->status) }}
                                                                            </a>

                                                                        </div>
                                                                    </div>
                                                                    <hr>
                                                                @endforeach
                                                                <div class="text-center  m-b-5">
                                                                    {{ $posts->render() }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <span>تا بحال پستی منتشر نکرده اس</span>
                                            @endif
                                        </div>
{{--                                        <div role="tabpanel" class="tab-pane fade" id="statusProfile">--}}
{{--                                            <b>تایید / رد / در انتظار تایید</b>--}}
{{--                                            @if(isset($user->detail) && !empty($user->detail))--}}
{{--                                                <a @can('panel.users.statusProfile') href="{{ route('panel.users.statusProfile',$user)  }}" @endcan>--}}
{{--                                                    {{ \App\Utility\Status::getStatusJob($user->detail->status) }}--}}
{{--                                                </a>--}}
{{--                                            @endif--}}
{{--                                        </div>--}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Basic Examples -->

@stop


