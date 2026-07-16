@extends('panel-old.layout.master')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    @lang('cms.advertise')

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
                                          action="{{route('ads.store')}}">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                        <div class="form-group ">
                                            <label for="title" class="control-label col-lg-2">@lang('cms.name') </label>
                                            <div class="col-lg-10">
                                                <input class=" form-control" id="title" name="title" minlength="2"
                                                       type="text" required/>
                                            </div>
                                        </div>

                                        {{-- image --}}
                                        <div class="form-group ">
                                            <label for="images" class="control-label col-lg-2">
                                                @lang('cms.featuring-image')
                                            </label>
                                            <div class="col-md-10">
                                                <div class="input-group">
                                                  <span class="input-group-btn">
                                                    <a id="lfm" data-input="thumbnail0" data-preview="holder0"
                                                       class="btn btn-primary">
                                                      <i class="fa fa-picture-o"></i> @lang('cms.choose')
                                                    </a>
                                                  </span>
                                                    <input id="thumbnail0" class="form-control" type="text"
                                                           name="filepath">
                                                </div>
                                            </div>

                                            <img id="holder2" style="margin-top:15px;max-height:100px;">
                                        </div>

                                        <div class="form-group ">
                                            <label for="cname"
                                                   class="control-label col-lg-2">@lang('cms.position')</label>
                                            <div class="col-lg-10">
                                                <select name="location" class="form-control select-option" id="">

                                                    <option value=" "> @lang('cms.position-adv')</option>
                                                    @if (isset($location))
                                                        @foreach($location as $key=>$value)
                                                            <option value="{{$key}}"> {{$value}} </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group ">
                                            <label for="link"
                                                   class="control-label col-lg-2">@lang('cms.path-link')</label>
                                            <div class="col-lg-10">
                                                <input class=" form-control" id="link" name="link"
                                                       type="text"/>
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <div class="col-lg-offset-2 col-lg-10">
                                                <input class="btn btn-success pull-left" type="submit"
                                                       value="@lang('cms.create-new-item')">

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
        @include('generals.sessionMessage')

        <div class="container">
            <section class="panel">
                <div class="panel-body">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>@lang('cms.name')</th>
                            <th>@lang('cms.address-link')</th>
                            <th>@lang('cms.position')</th>
                            <th>@lang('cms.status')</th>
                            <th>@lang('cms.picture')</th>
                            <th>@lang('cms.operation')</th>
                        </tr>
                        </thead>

                        <tbody>
                        @if(isset($advertise) && $advertise->count() > 0)
                            <?php $i = 1 ?>
                            @foreach($advertise as $val)
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td>{{ $val->title }}</td>
                                    <td>{{ isset($val->link) && !empty($val->link) ? $val->link : "--" }}</td>
                                    <td>{{ \App\Utility\location::getLocation($val->location) }}</td>
                                    <td>
                                        <a href="{{route('ads.status' ,['id' => $val->id] )}}"> {{ \App\Utility\Status::getStatus($val->status) }} </a>
                                    </td>
                                    <td>
                                        @if (isset($val) && !empty($val))
                                            @if(isset($val->image) && !empty($val->image[0]->url))
                                                @if(file_exists(base_path().$val->image[0]->url))
                                                    <img width="100" src="{{ $val->image[0]->url }}"
                                                         alt="{{$val->title}}">
                                                @endif
                                            @endif
                                        @endif
                                    </td>

                                    <td>
                                        <button class="btn btn-primary btn-xs" title="@lang('cms.edit')"
                                                data-toggle="modal"
                                                href="#edit{{ $val->id }}"><i class="icon-pencil"></i></button>
                                        <button class="btn btn-danger btn-xs" title="@lang('cms.delete')"
                                                data-toggle="modal"
                                                href="#delete{{ $val->id }}"><i class="icon-trash "></i></button>
                                    </td>
                                </tr>
                                <?php $i++ ?>
                            @endforeach
                        @endif
                        </tbody>

                    </table>

                    @if(isset($advertise) && $advertise->count() > 0)
                        <span style="margin-right: 45%">{!! $advertise->render() !!}</span>
                    @endif
                </div>
            </section>


            <!-- Modal edit -->
            @if(isset($advertise) && $advertise->count() > 0)
                @foreach($advertise as $val)
                    <div class="modal fade" id="edit{{ $val->id }}" tabindex="-1" role="dialog"
                         aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close close-white" data-dismiss="modal"
                                            aria-hidden="true">
                                        &times;
                                    </button>
                                    <h4 class="modal-title">@lang('cms.edit-item')</h4></div>
                                <div class="modal-body">

                                    <form class="cmxform form-horizontal tasi-form" id="commentForm" method="post"
                                          action="{{route('ads.update',['id' => $val->id])}}">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="_method" value="PATCH">

                                        <div class="form-group ">
                                            <label for="title" class="control-label col-lg-2">@lang('cms.name') </label>
                                            <div class="col-lg-10">
                                                <input class=" form-control" value="{{$val->title}}" id="title"
                                                       name="title" minlength="2" type="text" required/>
                                            </div>
                                        </div>


                                        {{-- image --}}
                                        <div class="form-group ">
                                            <label for="images"
                                                   class="control-label col-lg-2">@lang('cms.featuring-image')
                                            </label>
                                            <div class="col-md-10">
                                                <div class="input-group">
                                                  <span class="input-group-btn">
                                                    <a id="" data-input="thumbnail{{$val->id}}"
                                                       data-preview="holder{{$val->id}}"
                                                       class="btn btn-primary lfm1">
                                                      <i class="fa fa-picture-o"></i> @lang('cms.choose')
                                                    </a>
                                                  </span>
                                                    @if (isset($val) && !empty($val))
                                                        @if(isset($val->image) && !empty($val->image[0]->url))
                                                            @if(file_exists(base_path().$val->image[0]->url))
                                                                <input id="thumbnail{{$val->id}}" class="form-control"
                                                                       type="text"
                                                                       value="{{$val->image[0]->url}}"
                                                                       name="filepath">
                                                            @endif
                                                        @endif
                                                    @endif
                                                </div>
                                                @if (isset($val) && !empty($val))
                                                    @if(isset($val->image) && !empty($val->image[0]->url))
                                                        @if(file_exists(base_path().$val->image[0]->url))
                                                <img id="holder{{$val->id}}"
                                                     src="{{ url($val->image[0]->url) }}"
                                                     style="margin-top:15px;max-height:100px;">
                                                        @endif
                                                    @endif
                                                @endif
                                                <br>
                                                <img id="holder{{$val->id}}" style="margin-top:15px;max-height:100px;">
                                                <br>
                                            </div>
                                        </div>

                                        <div class="form-group ">
                                            <label for="cname"
                                                   class="control-label col-lg-2">@lang('cms.position') </label>
                                            <div class="col-lg-10">
                                                <select name="location" class="form-control select-option" id="">
                                                    <option value=" "> @lang('cms.position-adv')</option>

                                                    @if(isset($location))

                                                        @foreach($location as $key=>$value)
                                                            <option
                                                                value="{{$key}}" {{ isset($val) > 0 && $val->location == $key ? "selected" : null  }}> {{$value}} </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group ">
                                            <label for="link"
                                                   class="control-label col-lg-2">@lang('cms.path-link')</label>
                                            <div class="col-lg-10">
                                                <input class=" form-control" id="link" name="link"
                                                       type="text" value="{{$val->link}}"/>
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <div class="col-lg-offset-2 col-lg-10">
                                                <input class="btn btn-warning pull-left" type="submit"
                                                       value="@lang('cms.edit')">

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
        <!-- /.modal edit -->
        @endforeach
        @endif


    <!-- Modal delete -->
        @if(isset($advertise) && $advertise->count() > 0)
            @foreach($advertise as $val)
                <div class="modal fade" id="delete{{ $val->id }}" tabindex="-1" role="dialog"
                     aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;
                                </button>
                                <h4 class="modal-title">@lang('cms.alert')</h4>
                            </div>
                            <div class="modal-body">
                                <p>
                                    @lang('cms.question-delete')
                                </p>
                            </div>
                            <div class="modal-footer">
                                <form action="<?= route('ads.destroy', ['id' => $val->id]); ?>" method="POST">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="_method" value="DELETE">

                                    <input type="submit" name="btndelete" value="@lang('cms.delete')"
                                           class="btn btn-danger">
                                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">
                                        @lang('cms.cancel')
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
    @endif
    </div>

    </section>
    </div>
    </div>

@endsection
