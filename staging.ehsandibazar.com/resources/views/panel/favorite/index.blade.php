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
                    </h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        @if (isset($favorites) && count($favorites) > 0)
                            <table class="table table-bordered table-striped table-hover js-basic-example ">
                                <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">نام محصول </th>
                                    <th class="text-center">قیمت </th>
                                    <th class="text-center"> تصویر </th>
                                    <th class="text-center"> عملیات</th>
                                </tr>
                                </thead>
                                <tbody>

                                    @foreach($favorites as $val)

                                        <tr>
                                            <td class="text-center"> {{ $loop->iteration  }} </td>
                                            <td class="text-center"><a href="{{ isset($val->favoriteable->title) ? $val->favoriteable->path() : "" }}" target="_blank">
                                                    {{ isset($val->favoriteable->title) ? $val->favoriteable->title : "" }}
                                                </a>
                                            </td>
                                            <td class="text-center"> {{ isset($val->favoriteable->price) ? $val->favoriteable->price : "" }} </td>
                                            <td class="text-center">
                                                 @if(isset($val->favoriteable) && !empty($val->favoriteable) &&
                                                     isset($val->favoriteable->image[0]))
                                                  <img src="{{ $val->favoriteable->image[0]->url }}" alt="{{$val->favoriteable->title}}" class="img-thumbnail img-100">
                                                 @endif
                                              </td>

                                            <td class="text-center">

                                                @can('panel.favorite.delete')
                                                    <button type="button"
                                                            class="btn btn-danger btn-circle waves-effect waves-circle waves-float"
                                                            data-toggle="modal" data-target="#deleteModel{{$val->id}}">
                                                        <i class="material-icons">delete</i>
                                                    </button>
                                                @endcan

                                            </td>
                                        </tr>

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
                                                            action="{{ route('panel.favorite.delete' , ['id' => $val->id])  }}"
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


                                    @endforeach

                                </tbody>
                                <tfoot>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">نام محصول </th>
                                    <th class="text-center">قیمت </th>
                                    <th class="text-center"> تصویر </th>
                                    <th class="text-center"> عملیات</th>
                                </tr>
                                </tfoot>

                            </table>

                        @else
                            <p class="alert alert-info text-center"> رکوردی یافت نشد. </p>
                        @endif
                        <div class="container">
                            <div class="pull-left">
                                @if(isset($favorites) && $favorites->count() > 0)
                                    <span style="margin-right: 45%">{!! $favorites->render() !!}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Basic Examples -->

@stop

