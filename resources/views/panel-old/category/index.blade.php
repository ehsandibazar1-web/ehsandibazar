@extends('panel-old.layout.master')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <section class="panel padding-bottom-panel">
                <br>
                <header class="panel-heading">
                    @lang('cms.header-category-article-list')
                    <a type="button" class="btn btn-xs btn-success top-left" data-toggle="modal"
                       href="{{route('panel.category.create')}}">@lang('cms.create-new-item')
                    </a>
                </header>


            </section>
        </div>

        @include('generals.allErrors')
        @include('generals.sessionMessage')

        <div class="container">
            <section class="panel">
                <div class="panel-body">
                    @if(auth()->user()->level != "admin")
                        <p class="alert alert-info border-right-info">
                           @lang('cms.alert-accept')
                        </p>
                    @endif
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>@lang('cms.num')</th>
                            <th>@lang('cms.title')</th>
                            <th>@lang('cms.category')</th>
                            <th>@lang('cms.status')</th>
                            <th>@lang('cms.operation')</th>
                        </tr>
                        </thead>

                        <tbody>
                        @if(isset($category) && count($category) > 0)
                            @foreach($category as $val)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ str_limit($val->title , 20) }}</td>
                                    <td>{{ \App\Utility\parentName::getName($val->parent_id) }}</td>
                                    <td>
                                        <a href="{{route('panel.category.status' , ['id' => $val->id])}}">{{  \App\Utility\Status::getStatus($val->status)  }}</a>
                                    </td>

                                    <td>

                                        <a class="btn btn-warning btn-xs" title="@lang('cms.edit')"
                                           href="{{ route('panel.category.edit',['id'=>$val->id ]) }}"><i
                                                class="icon-edit "></i></a>
                                                
                                                
                                                  @can('delete')
                                            <button class="btn btn-danger btn-xs" title="@lang('cms.delete')" data-toggle="modal"
                                                    href="#delete{{$val->id}}"><i class="icon-trash "></i></button>
                                                    @endcan


                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>

                    </table>

                    @if(isset($category) && count($category) > 0)
                        <span style="margin-right: 45%">{!! $category->render() !!}</span>
                    @endif


                </div>
            </section>
        </div>


        <!-- Modal delete -->
        @if(isset($category) && count($category) > 0)
            @foreach($category as $val)
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
                                <form action="{{ route('panel.category.delete' , ['id' => $val->id])  }}" method="POST">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="_method" value="DELETE">

                                    <input type="submit" name="btndelete" value="@lang('cms.delete')" class="btn btn-danger">
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

