@extends('panel-old.layout.master')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <section class="panel padding-bottom-panel">
                <br>
                <header class="panel-heading">
                    @lang('cms.list-user-product')
                    <a type="button" class="btn btn-xs btn-success top-left" data-toggle="modal"
                       href="{{route('panel.request.product.create')}}">@lang('cms.create-new-item')
                    </a>

                </header>
            </section>
            <p class="alert alert-info border-right-info"> @lang('cms.alert-item-accept-admin')</p>
        </div>

        @include('generals.allErrors')
        @include('generals.sessionMessage')

        <div class="container">
            <section class="panel">
                <div class="panel-body">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>@lang('cms.num')</th>
                            <th>@lang('cms.title')</th>
                            <th>@lang('cms.featuring-image')</th>
                            <th>@lang('cms.category')</th>
                            <th>@lang('cms.status')</th>
                            <th>@lang('cms.operation')</th>
                        </tr>
                        </thead>

                        <tbody>
                        @if(isset($requestProduct) && count($requestProduct) > 0)
                            @foreach($requestProduct as $val)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        @if($val->product_id != 0)
                                            {{ str_limit($val->product->title , 20) }}
                                        @else
                                            {{"درخواستی"}}
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($val->image) && !empty($val->image))
                                            @php
                                                $explod = explode(",",$val->image);
                                            @endphp
                                            <img width="100px" height="100px" src="{{$explod[0]}}" alt="picture">
                                        @else
                                            {{ "-" }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($val->product_id != 0)
                                            {{$val->product->categoryproduct->title}}
                                        @else
                                            {{"-"}}
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ auth()->user()->isAdmin() || auth()->user()->isSuperAdmin() ? route('panel.request.product.status' , ['id' => $val->id]) : null}}">{{  \App\Utility\Status::getStatus($val->status,0)  }}</a>
                                    </td>

                                    <td>


                                        <a class="btn btn-warning btn-xs" title="@lang('cms.edit')"
                                           href="{{ route('panel.request.product.edit',['id'=>$val->id ]) }}"><i
                                                class="icon-edit "></i></a>


                                        <a class="btn btn-info btn-xs" data-toggle="modal" title="@lang('cms.show')"
                                           href="#show{{$val->id}}">
                                            <i class="icon-eye-open"></i>
                                        </a>

                                            @can('delete')
                                        <button class="btn btn-danger btn-xs" title="@lang('cms.delete')"
                                                data-toggle="modal"
                                                href="#delete{{$val->id}}"><i class="icon-trash "></i></button>
                                                @endcan

                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>

                    </table>

                    @if(isset($requestProduct) && count($requestProduct) > 0)
                        <span style="margin-right: 45%">{!! $requestProduct->render() !!}</span>
                    @endif


                </div>
            </section>
        </div>


        <!-- Modal show -->
        @if(isset($requestProduct) && count($requestProduct) > 0)
            @foreach($requestProduct as $val)
                <div class="modal fade" id="show{{ $val->id }}" tabindex="-1" role="dialog"
                     aria-labelledby="myModalLabel"
                     aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;
                                </button>
                                <h4 class="modal-title">@lang('cms.show-details')</h4>
                            </div>
                            <div class="modal-body">

                                @lang('cms.description') : {!! $val->description !!}

                                <hr>

                                @if(!empty($val->details))
                                    @lang('cms.details') : {!! $val->details !!}
                                @endif


                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">
                                    @lang('cms.cancel')
                                </button>
                            </div>

                            <div id="reload" class="hidden-button">
                                <img src="{{url('admin_theme/img/reload.gif')}}" alt="">
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


    <!-- Modal delete -->
    @if(isset($requestProduct) && count($requestProduct) > 0)
        @foreach($requestProduct as $val)
            <div class="modal fade" id="delete{{ $val->id }}" tabindex="-1" role="dialog"
                 aria-labelledby="myModalLabel"
                 aria-hidden="true">
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
                            <form action="{{ route('panel.request.product.delete' , ['id' => $val->id])  }}"
                                  method="POST">
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


@endsection

