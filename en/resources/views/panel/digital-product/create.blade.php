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
                            @can('panel.article.index')
                                <a href="{{ route('panel.digitalProduct.index')  }}"
                                   class="btn btn-outline-default btn-border-radius"> فهرست </a>
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
                                @can('panel.article.index')
                                    <li>
                                        <a href="{{ route('panel.digitalProduct.index')  }}" style="font-size: 10px">فهرست</a>
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
                    <form class="form-horizontal" method="post"
                          action="{{ route('panel.digitalProduct.store')  }}" enctype="multipart/form-data">
                        @csrf


                        {{-- users --}}
                        <div class="row clearfix">
                            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                <label for="status">کاربران
                                    <span class="redAlert">*</span>
                                </label>
                            </div>
                            <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                <div class="form-group">
                                    <div class="">
                                        <select class="form-group select-option select2" name="user">
                                            @foreach($users as $user)
                                                <option value="{{ $user->id  }}">{{ $user->name." ".$user->family."-".$user->mobile  }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- products --}}
                        <div class="row clearfix">
                            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                <label for="status">محصولات
                                    <span class="redAlert">*</span>
                                </label>
                            </div>
                            <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                <div class="form-group">
                                    <div class="">
                                        <select class="form-control select-option select2" name="product[]"  multiple="multiple">
                                            @foreach($products as $product)
                                                <option value="{{ $product->id  }}">{{ $product->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>


                        @can('panel.digitalProduct.store')
                            {{-- button --}}
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-7 ">
                                    <button type="submit" class="btn-hover color-1 pull-left">ذخیره
                                    </button>
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
@section('admin-js')
    @include('panel.layout.ckjs')
@endsection
