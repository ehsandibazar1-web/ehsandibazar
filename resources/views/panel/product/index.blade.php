@extends('panel.layout.master')

@section('top-menu')
    @include('panel.layout.partials.topNav')
@stop

@section('right-menu')
    @include('panel.layout.partials.rightNav')
@stop

@section('content')

    <!-- Basic Examples -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        {{ $title ?? "" }}
                        <div class="pull-left margin-5">
                            @can('panel.product.create')
                                <a href="{{ route('panel.product.create')  }}"
                                   class="btn btn-outline-default btn-border-radius"> ایجاد محصول جدید </a>
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
                                @can('panel.product.create')
                                    <li>
                                        <a href="{{ route('panel.product.create')  }}" style="font-size: 10px">
                                            ایجاد محصول جدید
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    </ul>

                </div>
                <div class="body">
                    <div class="table-responsive">
                        @if (isset($products) && count($products) > 0)
                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">عنوان</th>
                                    <th>@lang('cms.type-product')</th>
                                    <th class="text-center">دسته بندی</th>
                                    <th class="text-center">تصویر</th>
                                    <th class="text-center"> وضعیت</th>
                                    <th class="text-center"> عملیات</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($products as $product)

                                    <tr>
                                        <td class="text-center"> {{ $loop->iteration  }} </td>
                                        <td class="text-center">
                                            <a href="{{ $product->path() }}" target="_blank">{{ $product->title }}</a>
                                        </td>
                                        <td class="text-center">{{ \App\Utility\ProductType::productType($product->type) }}</td>

                                        <td class="text-center">{{ isset($product->categories[0]) ? $product->categories[0]->title : null }}</td>
                                        <td class="text-center"><img class="img-100 img-thumbnail"
                                                                     src="{{ isset($product->image[0]) ? $product->image[0]->url : null }}"
                                                                     alt="{{ $product->title }}"></td>
                                        <td class="text-center">
                                            <a @can('panel.product.create') href="{{ route('panel.product.status' , ['id' => $product->id])  }}" @endcan>
                                                {{ \App\Utility\Status::getStatus($product->status) }}
                                            </a>
                                        </td>


                                        <td class="text-center">

                                            @can('panel.product.modalDetails')
                                                <button type="button"
                                                        class="btn bg-light-blue btn-circle waves-effect waves-circle waves-float"
                                                        data-toggle="modal" data-target="#detailsModel{{$product->id}}">
                                                    <i class="material-icons">search</i>
                                                </button>
                                            @endcan

                                            @can('panel.product.edit')
                                                <a href="{{ route('panel.product.edit',['id' => $product->id]) }}"
                                                   class="btn btn-warning btn-circle waves-effect waves-circle waves-float">
                                                    <i class="material-icons">update</i>
                                                </a>
                                            @endcan

                                            @can('panel.product.delete')
                                                <button type="button"
                                                        class="btn btn-danger btn-circle waves-effect waves-circle waves-float"
                                                        data-toggle="modal" data-target="#deleteModel{{$product->id}}">
                                                    <i class="material-icons">delete</i>
                                                </button>
                                            @endcan

                                        </td>
                                    </tr>

                                    @can('panel.product.modalDetails')
                                        {{-- modal details --}}
                                        <div class="modal fade" id="detailsModel{{$product->id}}" tabindex="-1"
                                             role="dialog"
                                             aria-labelledby="exampleModalLabel"
                                             aria-hidden="true">
                                            <div class="modal-dialog modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"
                                                            id="exampleModalLabel">{{ $product->title }}</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <img
                                                            src="{{ isset($product->image[0]) ? $product->image[0]->url : null }}"
                                                            alt="{{ $product->title }}">
                                                        <hr>
                                                        <p> توضیحات : <span> {!! $product->body !!} </span></p>
                                                        @if(isset($product->tags) && !empty($product->tags) && count($product->tags) > 0)
                                                        <hr>
                                                            <label for="tags">برچسب ها :</label>
                                                            @foreach($product->tags as $tag)
                                                                <span
                                                                    class="label label-primary">{{ $tag->title }}</span>
                                                            @endforeach
                                                        @endif
                                                        <hr>
                                                        @if(isset($product->countries) && !empty($product->countries))
                                                            <label for="tags">کشور ها :</label>
                                                            @foreach($product->countries as $country)
                                                                <span
                                                                    class="label label-danger">{{ $country->fa_name }}</span>
                                                            @endforeach
                                                        @endif
                                                        <hr>
                                                        <span
                                                            class="label label-danger m-1">تعداد بازدید : {{ $product->viewCount }}</span>
                                                        <span
                                                            class="label label-success m-1">تعداد دیدگاه : {{ $product->commentCount }}</span>
                                                        <span
                                                            class="label label-warning m-1">دسته بندی : {{ isset($product->categories[0]) ? $product->categories[0]->title : null }}</span>

                                                        <span
                                                            class="label label-primary m-1">ایجاد کننده : {{ $product->user->name }}</span>
                                                        <span
                                                            class="label label-default m-1">زمان مطالعه : {{ $product->study_time }}</span>
                                                        <hr>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default waves-effect"
                                                                data-dismiss="modal">انصراف
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- modal details --}}
                                    @endcan

                                    @can('panel.product.delete')
                                        {{-- modal delete --}}
                                        <div class="modal fade" id="deleteModel{{$product->id}}" tabindex="-1"
                                             role="dialog" aria-labelledby="exampleModalLabel"
                                             aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">هشدار!</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        آیا از حذف آیتم مورد نظر اطمینان دارین؟
                                                    </div>
                                                    <div class="modal-footer">
                                                        <form
                                                            action="{{ route('panel.product.delete' , ['id' => $product->id])  }}"
                                                            method="post">
                                                            @csrf
                                                            {{ method_field('DELETE')  }}
                                                            <button type="submit" class="btn btn-danger waves-effect">
                                                                حذف
                                                            </button>
                                                        </form>

                                                        <button type="button" class="btn btn-default waves-effect"
                                                                data-dismiss="modal">انصراف
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- modal delete --}}
                                    @endcan


                                @endforeach

                                </tbody>
                                <tfoot>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">عنوان</th>
                                    <th>@lang('cms.type-product')</th>
                                    <th class="text-center">دسته بندی</th>
                                    <th class="text-center">تصویر</th>
                                    <th class="text-center"> وضعیت</th>
                                    <th class="text-center"> عملیات</th>
                                </tr>
                                </tfoot>

                            </table>

                        @else
                            <p class="alert alert-info text-center"> رکوردی یافت نشد. </p>
                        @endif
                        <div class="container">
                            <div class="pull-left">
{{--                                @if(isset($products) && $products->count() > 0)--}}
{{--                                    <span style="margin-right: 45%">{!! $products->render() !!}</span>--}}
{{--                                @endif--}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Basic Examples -->


@stop

