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
                        {{ isset($title) ? $title : "" }}
                        <div class="pull-left margin-5">
                            @can('panel.news.create')
                                <a href="{{ route('panel.news.create')  }}"
                                   class="btn btn-outline-default btn-border-radius"> ایجاد خبر جدید </a>
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
                                @can('panel.news.create')
                                    <li>
                                        <a href="{{ route('panel.news.create')  }}" style="font-size: 10px">
                                            ایجاد خبر جدید
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    </ul>

                </div>
                <div class="body">
                    <div class="table-responsive">
                        @if (isset($news) && count($news) > 0)
                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable ">
                                <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">عنوان</th>
                                    <th class="text-center">دسته بندی</th>
                                    <!--<th class="text-center">تصویر</th>-->
                                    <th class="text-center"> وضعیت</th>
                                    <th class="text-center"> عملیات</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($news as $val)

                                    <tr>
                                        <td class="text-center"> {{ $loop->iteration  }} </td>
                                        <td class="text-center">
                                            <a href="{{ $val->shortUrl() }}" target="_blank">{{ $val->title }}</a>
                                        </td>
                                        <td class="text-center">{{ isset($val->categories[0]) ? $val->categories[0]->title : '' }}</td>
                                       
                                        <td class="text-center">
                                            <a @can('panel.news.create') href="{{ route('panel.news.status' , ['id' => $val->id])  }}" @endcan>
                                                {{ \App\Utility\Status::getStatus($val->status) }}
                                            </a>
                                        </td>


                                        <td class="text-center">

                                            @can('panel.news.modalDetails')
                                                <button type="button"
                                                        class="btn bg-light-blue btn-circle waves-effect waves-circle waves-float"
                                                        data-toggle="modal" data-target="#detailsModel{{$val->id}}">
                                                    <i class="material-icons">search</i>
                                                </button>
                                            @endcan

                                            @can('panel.news.edit')
                                                <a href="{{ route('panel.news.edit',['id' => $val->id]) }}"
                                                   class="btn btn-warning btn-circle waves-effect waves-circle waves-float">
                                                    <i class="material-icons">update</i>
                                                </a>
                                            @endcan

                                            @can('panel.news.delete')
                                                <button type="button"
                                                        class="btn btn-danger btn-circle waves-effect waves-circle waves-float"
                                                        data-toggle="modal" data-target="#deleteModel{{$val->id}}">
                                                    <i class="material-icons">delete</i>
                                                </button>
                                            @endcan

                                        </td>
                                    </tr>
                                   

                                    @can('panel.news.delete')
                                        {{-- modal delete --}}
                                        <div class="modal fade" id="deleteModel{{$val->id}}" tabindex="-1"
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
                                                            action="{{ route('panel.news.delete' , ['id' => $val->id])  }}"
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
                                    <th class="text-center">دسته بندی</th>
                                    <!--<th class="text-center">تصویر</th>-->
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
{{--                                @if(isset($news) && $news->count() > 0)--}}
{{--                                    <span style="margin-right: 45%">{!! $news->render() !!}</span>--}}
{{--                                @endif--}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Basic Examples -->
  @foreach($news as $val)
   @can('panel.news.modalDetails')
    {{-- modal details --}}
    <div class="modal fade" id="detailsModel{{$val->id}}" tabindex="-1"
         role="dialog"
         aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"
                        id="exampleModalLabel">{{ $val->title }}</h5>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <img
                        src="{{ isset($val->image[0]) ? $val->image[0]->url : null }}"
                        alt="{{ $val->title }}">
                    <hr>
                    <p> توضیحات : <span> {!! $val->description !!} </span></p>
                    @if(isset($val->tags) && !empty($val->tags) && count($val->tags) > 0)
                        <hr>
                        <label for="tags">برچسب ها :</label>
                        @foreach($val->tags as $tag)
                            <span
                                class="label label-primary">{{ $tag->title }}</span>
                        @endforeach
                    @endif
                    <hr>

                    @if(isset($val->countries) && !empty($val->countries))
                        <label for="tags">کشور ها :</label>
                        @foreach($val->countries as $country)
                            <span
                                class="label label-danger">{{ $country->fa_name }}</span>
                        @endforeach
                    @endif
                    <hr>
                    <span
                        class="label label-danger m-1">تعداد بازدید : {{ $val->viewCount }}</span>
                    <span
                        class="label label-success m-1">تعداد دیدگاه : {{ $val->commentCount }}</span>
                    <span
                        class="label label-warning m-1">دسته بندی : {{  isset($val->categories[0]) ? $val->categories[0]->title  : '' }}</span>
                    <br>
                    <br>
                    <span
                        class="label label-primary m-1">ایجاد کننده : {{ $val->user->name }}</span>
                    <span
                        class="label label-default m-1">زمان مطالعه : {{ $val->study_time }}</span>
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

  @endforeach


@stop

