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
                            @can('panel.permission.index')
                                <a href="{{ route('panel.permission.index')  }}"
                                   class="btn btn-outline-default btn-border-radius"> لیست سطوح دسترسی </a>
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
                                @can('panel.permission.index')
                                    <li>
                                        <a href="{{ route('panel.permission.index')  }}" style="font-size: 10px">
                                            لیست سطوح دسترسی
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
                    @if (isset($find) && !empty($find) && !is_null($find))
                        <form class="form-horizontal" method="post"
                              action="{{ route('panel.permission.update' , ['id' => $find->id])  }}">
                            {{ method_field('PATCH')  }}
                            @endif
                            <form class="form-horizontal" method="post" action="{{ route('panel.permission.store')  }}">
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
                                                <input name="name" value="{{ isset($find) ? $find->name : "" }}"
                                                       {{ isset($find) ? "disabled" : ""   }} type="text" id="name"
                                                       class="form-control"
                                                       placeholder="نام دسترسی خود را بنویسید">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- label --}}
                                <div class="row clearfix">
                                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                        <label for="label">برچسب
                                        </label>
                                    </div>
                                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input name="label" type="text"
                                                       value="{{ isset($find) ? $find->label : "" }}" id="label"
                                                       class="form-control"
                                                       placeholder="برچسب برای تفکیک">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- method --}}
                                <div class="row clearfix">
                                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                        <label for="method">متد
                                            <span class="redAlert">*</span>
                                        </label>
                                    </div>
                                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <select {{ isset($find) ? "disabled" : ""  }} name="method" id="method">
                                                    @foreach(\App\Utility\methods::getMethods() as $item)
                                                        <option {{ isset($find) && $find->method == $item ? "selected" : "" }} value="{{ $item  }}">{{ $item  }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if (isset($find) && !empty($find) && !is_null($find) )
                                    @can('panel.permission.update')
                                        <div class="row clearfix">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-7 ">
                                                <button type="submit" class="btn-hover color-1 pull-left">ویرایش
                                                </button>
                                            </div>
                                        </div>
                                    @endcan
                                @else
                                    @can('panel.permission.store')
                                        {{-- button edit --}}
                                        <div class="row clearfix">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-7 ">
                                                <button type="submit" class="btn-hover color-1 pull-left">ذخیره</button>
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