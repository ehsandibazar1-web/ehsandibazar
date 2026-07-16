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
                            @can('panel.LevelManage.index')
                                <a href="{{ route('panel.LevelManage.index')  }}"
                                   class="btn btn-outline-default btn-border-radius">مدیریت نقش کاربران </a>
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
                                @can('panel.LevelManage.index')
                                    <li>
                                        <a href="{{ route('panel.LevelManage.index')  }}" style="font-size: 10px">
                                            مدیریت نقش کاربران
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

                    @if (isset($user) && !empty($user) && !is_null($user))
                        <form class="form-horizontal" method="post"
                              action="{{ route('panel.LevelManage.update' , ['user'=> $user])  }}">
                            {{ method_field('PATCH')  }}
                            @else
                                <form class="form-horizontal" method="post" action="{{ route('panel.LevelManage.store')  }}">
                                    @endif
                                    @csrf


                                    @if (!isset($user) && empty($user))
                                    {{-- users --}}
                                    <div class="row clearfix">
                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                            <label for="title">کاربران
                                            </label>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                @if (isset($users) && !empty($users) && count($users) > 0)
                                                    <select name="user_id" id="user_id"  multiple="multiple" data-select="false">
                                                        @foreach($users as $itemUser)
                                                            <option value="{{ $itemUser->id }}">{{ $itemUser->name." ".$itemUser->family }}</option>
                                                        @endforeach
                                                    </select>
                                                @else
                                                    <p>  کاربری وجود ندارد </p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    {{-- roles --}}
                                    <div class="row clearfix">
                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                            <label for="title">نقش های کاربری
                                            </label>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                @if (isset($roles) && !empty($roles) && count($roles) > 0)
                                                    <select name="role_id[]" multiple="multiple" >
                                                        @foreach($roles as $role)
                                                            <option value="{{ $role->id }}" {{ isset($user) && $user->hasRole($role->name) ? 'selected' : '' }}>{{ $role->name }} - {{ $role->label }}</option>
                                                        @endforeach
                                                    </select>
                                                @else
                                                    <p> نقش کاربری وجود ندارد </p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>






                                    @if (isset($user) && !empty($user) && !is_null($user))
                                        @can('panel.role.update')
                                            {{-- button edit --}}
                                            <div class="row clearfix">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-7 ">
                                                    <button type="submit" class="btn-hover color-4 pull-left">ویرایش
                                                    </button>
                                                </div>
                                            </div>
                                        @endcan
                                    @else
                                        @can('panel.role.store')
                                            {{-- button save --}}
                                            <div class="row clearfix">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-7 ">
                                                    <button type="submit" class="btn-hover color-1 pull-left">ذخیره
                                                    </button>
                                                </div>
                                            </div>
                                        @endcan
                                    @endif

                                </form>
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Horizontal Layout -->

@stop



