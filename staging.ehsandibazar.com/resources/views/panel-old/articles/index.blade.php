@extends('panel-old.layout.master')

@section('content')
    <div class="row">

        <div class="col-lg-12">
            <section class="panel">

                <header class="panel-heading " style="border:none;border-bottom:1px solid #eff2f7;overflow: hidden">
                    <div class="col-lg-6">
                        @lang('cms.list-article')
                        <a class="btn btn-xs btn-success" href="{{route('panel.article.create')}}">@lang('cms.create-new-item')</a>
                    </div>
                    <div class="col-lg-6" style="text-align: left;">

                        <form action="{{ Url('panel/articlefilter/')}}" method="post">
                            @csrf
                            <div class="form-group ">
                                <label for="cemail" class="control-label col-lg-2"> </label>
                                <div class="col-lg-4">
                                    <input type="text" class="form-control" name="title" placeholder="@lang('cms.title')"
                                    >
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-lg-offset-2 col-lg-2">
                                    <input class="btn btn-xs btn-danger" type="submit" value="@lang('cms.search')">
                                </div>
                            </div>
                        </form>

                    </div>


                </header>
                <div style="clear:both"></div>
                <div class="container row" style="margin-top:5px;">

                </div>
                <div class="panel-body">

                    <table class="table table-hover" id="datatable">
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
                        @foreach($articles as $article)

                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><a target="_blank" href="{{ $article->path() }}">{{ str_limit($article->title,50) }}</a></td>
                                <td> {{ $article->category->title  }} </td>
                                <td>
                                    @if( count($article->image) > 0 && isset($article->image[0]) )
                                            <img width="100" src="{{ $article->image[0]->url }}" alt="{{str_limit($article->title,50)}}">
                                            @else
                                            <img src="{{url('general/img/404-error.png')}}" width="50" alt="\wpt">
                                    @endif
                                </td>
                                <td>
                                    <a href="{{route('panel.article.status' ,['id' => $article->id] )}}"> {{ \App\Utility\Status::getStatus($article->status) }} </a>
                                </td>
                                <td>

                                    <button class="btn btn-success btn-xs" title="@lang('cms.show')" data-toggle="modal"
                                            href="#show{{ $article->id }}"><i class="icon-eye-open"></i></button>
                                            
                                              @can('update')
                                    <a class="btn btn-primary btn-xs" title="@lang('cms.edit')"
                                       href="{{route('panel.article.edit',['id' => $article->id]) }}"><i
                                            class="icon-pencil"></i></a>
                                            @endcan
                                            
                                            @can('delete')
                                    <button class="btn btn-danger btn-xs" title="@lang('cms.delete')" data-toggle="modal"
                                            href="#delete{{ $article->id }}"><i class="icon-trash "></i></button>
                                            @endcan
                                </td>
                            </tr>
                        @endforeach
                        </tbody>

                    </table>

{{--                    <span style="margin-right: 45%">{!! $articles->render()  !!}</span>--}}

                    <!-- Modal show -->


                    @foreach($articles as $val)
                        <div class="modal fade " id="show{{ $val->id }}" tabindex="-1" role="dialog"
                             aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                            &times;
                                        </button>
                                        <h4 class="modal-title" style="font-family: yekan"><span class="">@lang('cms.show-item')</span>
                                        </h4>
                                    </div>
                                    <div class="modal-body text-center">

                                        @if(isset($val->image) && isset($val->image[0]))
                                                <img width="100" src="{{ $val->image[0]->url }}" alt="{{str_limit($article->title,50)}}">
                                            @else
                                                <img src="{{url('general/img/404-error.png')}}" width="50" alt="inten">
                                        @endif

                                        <h3 style="font-family: yekan">{{$val->title}}</h3>
                                        {!! $val->body !!}
                                        <hr>


                                        <span class="label label-default">@lang('cms.in-date') {{ $val->created_at }}</span>
                                        <span class="label label-primary"> @lang('cms.visit') {{ $val->viewCount }}</span>
                                        <span class="label label-danger"> @lang('cms.count-comment') {{ $val->commentCount }}</span>
                                        {{--       <span class="label label-success">دسته : {{ $val->category->name}}</span>--}}
                                        <span class="label label-default">@lang('cms.by') {{ $val->user->name }}</span>
                                    </div>

                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                @endforeach
                <!-- /.modal-dialog -->
                </div>
                <!-- /.modal show -->


                <!-- Modal delete -->
                @foreach($articles as $val)
                    <div class="modal fade" id="delete{{ $val->id }}" tabindex="-1" role="dialog"
                         aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                        &times;
                                    </button>
                                    <h4 class="modal-title">@lang('cms.alert')</h4>
                                </div>
                                <div class="modal-body">
                                    <p> @lang('cms.question-delete') </p>
                                </div>
                                <div class="modal-footer">
                                    <form action="{{ route('panel.article.delete' , ['id' => $val->id])  }}"
                                          method="POST">
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

    </div>

    </section>
    </div>
    </div>
s
@endsection

