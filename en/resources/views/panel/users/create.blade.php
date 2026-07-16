@extends('panel.layout.master')

@section('top-menu')
    @include('panel.layout.partials.topNav')
@stop

@section('right-menu')
    @include('panel.layout.partials.rightNav')
@stop

@section('content')

    <!-- Horizontal Layout -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        {{ isset($title) ? $title : "" }}
                        <div class="pull-left margin-5">
                            @can('panel.user.index')
                                <a href="{{ route('panel.users.index')  }}"
                                   class="btn btn-outline-default btn-border-radius"> لیست کاربران </a>
                            @endcan
                        </div>
                    </h2>

                    <ul class="header-dropdown m-r--5">
                        <li class="dropdown">
                            <a href="#" onClick="return false;" class="dropdown-toggle" data-toggle="dropdown"
                               role="button"
                               aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons">more_vert</i>
                            </a>
                            <ul class="dropdown-menu pull-right">
                                <li>
                                    <a href="{{ route('panel.dashboard.index')  }}">داشبورد</a>
                                </li>
                                @can('panel.users.index')
                                    <li>
                                        <a href="{{ route('panel.users.index')  }}" style="font-size: 10px">
                                            لیست کاربران
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="body">
                    @include('generals.allErrors')
                    @include('generals.sessionMessage')
                    <br>
                    @if(isset($find) && !empty($find))
                        <form class="form-horizontal" method="post"
                              action="{{ route('panel.users.update' , ['id' => $find->id ])  }}">
                            {{ method_field('PATCH') }}
                            @else
                                <form class="form-horizontal" method="post" action="{{ route('panel.users.store')  }}">
                                    @endif
                                    @csrf


                                    {{-- name --}}
                                    <div class="row clearfix">
                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                            <label for="name">نام
                                                <span class="redAlert">*</span>
                                            </label>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input name="name" type="text" id="name" class="form-control"
                                                           value="{{ isset($find) && !empty($find) ? $find->name : "" }}"
                                                           placeholder="نام کاربر مورد نظر خود را بنویسید">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- family --}}
                                    <div class="row clearfix">
                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                            <label for="family">نام خانوادگی
                                                <span class="redAlert">*</span>
                                            </label>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input name="family" type="text" id="family" class="form-control"
                                                           value="{{ isset($find) && !empty($find) ? $find->family : "" }}"
                                                           placeholder="نام خانوادگی کاربر مورد نظر خود را بنویسید">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- mobile --}}
                                    <div class="row clearfix">
                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                            <label for="mobile">موبایل
                                                <span class="redAlert">*</span>
                                            </label>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input name="mobile" type="text" id="mobile" class="form-control"
                                                           value="{{ isset($find) && !empty($find) ? $find->mobile : "" }}"
                                                           placeholder="موبایل مورد نظر خود را بنویسید">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- email --}}
                                    <div class="row clearfix">
                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                            <label for="email">ایمیل
                                            </label>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input name="email" type="text" id="email" class="form-control"
                                                           value="{{ isset($find) && !empty($find) ? $find->email : "" }}"
                                                           placeholder="ایمیل مورد نظر خود را بنویسید">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    

                                  
                                    {{-- role --}}
                                    <div class="row clearfix">
                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                            <label for="role">نقش کاربری
                                                <span class="redAlert">*</span>
                                            </label>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <select name="role[]" id="" multiple="multiple">
                                                        @if (isset($allRole) && !empty($allRole) && count($allRole) > 0)
                                                            @foreach($allRole as $itemRole)
                                                                <option
                                                                    {{ isset($find) &&!empty($find) && $find->hasRole($itemRole->name)  ? "selected" : ""  }}
                                                                    value="{{ $itemRole->id }}">{{ $itemRole->name }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    {{-- level --}}
                                    <div class="row clearfix">
                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                            <label for="role">سطح کاربری
                                                <span class="redAlert">*</span>
                                            </label>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <select name="level" id="">
                                                            @foreach(\App\Utility\Level::levelEach() as $key => $level)
                                                                <option
                                                                    {{ isset($find) &&!empty($find) && $find->level == $key ? "selected" : ""  }}
                                                                    value="{{ $key }}">{{ $level }}</option>
                                                            @endforeach

                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- password --}}
                                    <div class="row clearfix">
                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                            <label for="password">رمز عبور
                                                <span class="redAlert">*</span>
                                            </label>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input name="password" type="password" id="password"
                                                           class="form-control"
                                                           placeholder="رمز عبور مورد نظر خود را بنویسید">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @can('panel.users.store')
                                        {{-- button --}}
                                        <div class="row clearfix">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-7 ">
                                                <button type="submit" class="btn-hover color-1 pull-left">ذخیره</button>
                                            </div>
                                        </div>
                                    @endcan

                                </form>
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Horizontal Layout -->

@stop
