@extends('panel.layout.master')

@section('top-menu')
    @include('panel.layout.partials.topNav')
@stop

@section('right-menu')
    @include('panel.layout.partials.rightNav')
@stop
@section('admin-css')
    <link rel="stylesheet" href="{{ url('category-subcategory-assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/assets/css/drag-and-drop-style.css') }}">
@endsection
@section('admin-js')
    <script src="{{ url('category-subcategory-assets/js/jquery-3.5.1.slim.min.js') }}"></script>
    <script src="{{ url('category-subcategory-assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ url('category-subcategory-assets/js/popper.min.js') }}"></script>


    <script src="{{ asset('admin/assets/js/jquery.nestable.js') }}"></script>
    <script src="{{ asset('admin/assets/js/drag-and-drop-style.js') }}"></script>
@endsection
@section('content')

    <!-- Basic Examples -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        {{ isset($title) ? $title : "" }}
                        <div class="pull-left margin-5">
                            @can('panel.menu.create')
                                <a href="{{ route('panel.menu.create')  }}"
                                   class="btn btn-outline-default btn-border-radius"> ایجاد منو جدید </a>
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
                                @can('panel.menu.create')
                                    <li>
                                        <a href="{{ route('panel.menu.create')  }}" style="font-size: 10px">
                                            ایجاد منو جدید
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    </ul>

                </div>
                <div class="body">
                    @if(isset($menus) && !empty($menus) && count($menus) > 0)
                        <div class="row">
                            <div class="col-md-12 dd" id="nestable-wrapper">
                                <ol class="dd-list list-group">
                                    @foreach($menus as $k => $menu)
                                        <li class="dd-item list-group-item" data-id="{{ $menu->id }}"
                                            style="text-align: left">
                                            <div class="dd-handle">{{ $menu->title }}</div>
                                            <div class="dd-option-handle">
                                                <a href="{{ route('panel.menu.edit',$menu->id) }}"
                                                   class="btn btn-warning btn-circle waves-effect waves-circle waves-float">
                                                    <i class="material-icons">update</i>
                                                </a>
                                                <a href="{{ route('panel.menu.delete',$menu->id) }}"
                                                   class="btn btn-danger btn-circle waves-effect waves-circle waves-float">
                                                    <i class="material-icons">delete</i>
                                                </a>
                                            </div>

                                            @if(!empty($menu->menus))
                                                @include('panel.menu.partials.sub-menus', [ 'menu' => $menu])
                                            @endif
                                        </li>
                                    @endforeach
                                </ol>
                            </div>
                        </div>
                        <div class="row">
                            <form action="{{ route('panel.nested-menus.store') }}" method="post">
                                @csrf
                                <textarea style="display: none;" name="nested_category_array"
                                          id="nestable-output"></textarea>
                                <button type="submit" class="btn btn-success" style="margin-top: 15px;">بروز رسانی
                                </button>
                            </form>
                        </div>
                    @else
                        <p class="alert alert-info text-center"> رکوردی برای نمایش وجود ندارد. </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Basic Examples -->


@stop

