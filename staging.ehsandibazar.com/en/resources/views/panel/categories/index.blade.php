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
                        {{ $title ?? "" }}
                        <div class="pull-left margin-5">
                            @can('panel.category.create')
                                <a href="{{ route('panel.category.create')  }}"
                                   class="btn btn-outline-default btn-border-radius"> ایجاد دسته بندی جدید </a>
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
                                @can('panel.category.create')
                                    <li>
                                        <a href="{{ route('panel.category.create')  }}" style="font-size: 10px">ایجاد
                                            دسته
                                            بندی جدید</a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    </ul>

                </div>
                <div class="body">
                    @if(isset($categories) && !empty($categories) && count($categories) > 0)
                        <div class="row">
                            <div class="col-md-12 dd" id="nestable-wrapper">
                                <ol class="dd-list list-group">
                                    @php
                                        \App\Model\Category::$preventAttrSet = true;
                                    @endphp
                                    @foreach($categories->groupBy('type') as $group)
                                        <h3>{{ $group[0]->type ?? '' }}</h3>
                                        @foreach($group as $k => $category)
                                            <li class="dd-item list-group-item" data-id="{{ $category->id }}"
                                                style="text-align: left">
                                                <div class="dd-handle">{{ $category->title }}</div>
                                                <div class="dd-option-handle">
                                                    @if($category->isAttributable())
                                                        <a href="{{ route('panel.category.attributedForm',$category->id) }}"
                                                           title="انتخاب ویژگی ها"
                                                           class="btn btn-primary btn-circle waves-effect waves-circle waves-float">
                                                            <i class="material-icons">spellcheck</i>
                                                        </a>
                                                    @endif

                                                    @can('panel.category.update')
                                                        <a href="{{ route('panel.category.edit',$category->id) }}"
                                                           class="btn btn-warning btn-circle waves-effect waves-circle waves-float">
                                                            <i class="material-icons">update</i>
                                                        </a>
                                                    @endcan
                                                    @can('panel.category.delete')
                                                        <a href="{{ route('panel.category.delete',$category->id) }}"
                                                           class="btn btn-danger btn-circle waves-effect waves-circle waves-float">
                                                            <i class="material-icons">delete</i>
                                                        </a>
                                                    @endcan
                                                </div>

                                                @if(!empty($category->categories))
                                                    @include('panel.categories.partials.sub-cateogries', [ 'category' => $category])
                                                @endif
                                            </li>
                                        @endforeach
                                        <br><br>

                                    @endforeach

                                </ol>
                            </div>
                        </div>
                        <div class="row">
                            <form action="{{ route('panel.nested-categories.store') }}" method="post">
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

