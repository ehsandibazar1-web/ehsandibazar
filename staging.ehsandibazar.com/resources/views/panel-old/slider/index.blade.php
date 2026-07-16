@extends('panel-old.layout.master')
{{--@section('title')
    مدیریت | اسلایدر
@endsection--}}
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                   @lang('cms.slider-setting')

                    <button type="button" class="btn btn-xs btn-success" data-toggle="modal" href="#insert">
                        @lang('cms.create-new-item')
                    </button>

                    <!-- Modal insert -->

                    <div class="modal fade" id="insert" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                         aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                        &times;
                                    </button>
                                    <h4 class="modal-title">@lang('cms.create-new-item')</h4>
                                </div>
                                <div class="modal-body">

                                    <form class="cmxform form-horizontal tasi-form" id="commentForm" method="post"
                                          action="{{ Url('panel/slider/') }}" enctype="multipart/form-data">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                        <div class="form-group ">
                                            <label for="cname" class="control-label col-lg-2">@lang('cms.description')</label>
                                            <div class="col-lg-10">
                                                <input class=" form-control" id="cname" name="details" minlength="2"
                                                       type="text" required/>
                                            </div>
                                        </div>


                                        {{-- image --}}
                                        <div class="form-group ">
                                            <label for="cname" class="control-label col-lg-2">
                                                @lang('cms.alert-slider')
                                            </label>
                                            <div class="col-lg-10">
                                                <div class="form-group ">
                                                    <div class="col-md-12">
                                                        <div class="input-group">
                                                  <span class="input-group-btn">
                                                    <a id="lfm" data-input="thumbnail2" data-preview="holder2"
                                                       class="btn btn-primary">
                                                      <i class="fa fa-picture-o"></i> @lang('cms.choose')
                                                    </a>
                                                  </span>
                                                            <input id="thumbnail2" class="form-control" type="text"
                                                                   name="filepath">
                                                        </div>
                                                    </div>

                                                    @if (isset($slider) && $slider->count() > 0)
                                                        @if(isset($slider->image) && !empty($slider->image[0]) && file_exists(base_path().$slider->image[0]->url))
                                                            <img id="holder2"
                                                                 src="{{( $slider->image[0]->url)}}"
                                                                 style="margin-top:15px;max-height:100px;">
                                                            @else
                                                            <img width="50" src="{{url('general/img/404-error.png')}}" alt="inten">
                                                        @endif

                                                    @endif
                                                    <img id="holder2" style="margin-top:15px;max-height:100px;">
                                                </div>

                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <div class="col-lg-offset-2 col-lg-10">
                                                <input class="btn btn-success pull-left" type="submit" value="@lang('cms.save')">

                                            </div>
                                        </div>
                                    </form>
                                </div>


                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
        </div>
        <!-- /.modal insert -->
        </header>

        @include('generals.allErrors')
        @include('generals.sessionMessage');

        <section class="panel">
            <div class="panel-body">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>@lang('cms.num')</th>
                        <th>@lang('cms.description')</th>
                        <th>@lang('cms.picture')</th>
                        <th>@lang('cms.operation')</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($slider as $val)
                        <tr>
                            <td>{{ $val->id }}</td>
                            <td>{{ $val->details }}</td>
                            <td>
                                @if(isset($val->image) && !empty($val->image[0]) && file_exists(base_path().$val->image[0]->url))
                                <img src="{{ url($val->image[0]->url)}}" width="150"
                                     height="70">
                                    @endif
                            </td>

                            <td>
                                <button class="btn btn-success btn-xs" title="@lang('cms.show')" data-toggle="modal"
                                        href="#show{{ $val->id }}"><i class="icon-eye-open"></i></button>

                                <button class="btn btn-danger btn-xs" title="@lang('cms.delete')" data-toggle="modal"
                                        href="#delete{{ $val->id }}"><i class="icon-trash "></i></button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>

                </table>

                <span style="margin-right: 45%">{!! $slider->render() !!}</span>

                <!-- Modal show -->
                @foreach($slider as $val)
                    <div class="modal fade" id="show{{ $val->id }}" tabindex="-1" role="dialog"
                         aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                        &times;
                                    </button>
                                    <h4 class="modal-title"><span class="">@lang('cms.show-pic') </span></h4>
                                </div>
                                <div class="modal-body">
                                    <p>
                                        @if(isset($val->image) && !empty($val->image[0]) && file_exists(base_path().$val->image[0]->url))
                                        <img src="{{ url($val->image[0]->url)}}" width="500"
                                             height="300">
                                            @endif
                                    </p>

                                    <hr>
                                    <p>{{ $val->details }}</p>

                                </div>


                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
            @endforeach
            <!-- /.modal-dialog -->
            </div>
        </section>
        <!-- /.modal show -->

        <!-- Modal delete -->
        @foreach($slider as $val)
            <div class="modal fade" id="delete{{ $val->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title">@lang('cms.alert')</h4>
                        </div>
                        <div class="modal-body">
                            <p>
                                @lang('cms.question-delete')
                            </p>
                        </div>
                        <div class="modal-footer">
                            <form action="{{ route('slider.destroy',$val->id) }}" method="POST">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="_method" value="DELETE">

                                <input type="submit" name="btndelete" value="@lang('cms.delete')" class="btn btn-danger">
                                <button type="button" class="btn btn-default pull-right" data-dismiss="modal">@lang('cms.cancel')
                                </button>
                            </form>

                        </div>


                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
    </div>
    <!-- /.modal delete -->
    @endforeach
    </div>

    </section>
    </div>
    </div>

@endsection

