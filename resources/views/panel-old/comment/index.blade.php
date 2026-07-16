@extends('panel-old.layout.master')
{{--@section('title')
    مدیریت | دیدگاه ها
@endsection--}}
@section('content')
    <div class="row">

        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                   @lang('cms.list-comment')

                    @include('generals.allErrors')
                    @include('generals.sessionMessage')
                </header>


                <div class="panel-body">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>@lang('cms.num')</th>
                            <th>@lang('cms.name')</th>
                            <th>@lang('cms.show-item')</th>
                            <th>@lang('cms.status')</th>
                            <th>@lang('cms.operation')</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($comments as $comment)
                            <tr>
                                <td>{{ $comment->id }}</td>
                                <td>{{ $comment->user->name }}</td>
                                <td><a href="{{ $comment->commentable->path() }}" target="_blank">{{ !empty($comment->commentable->title) ? $comment->commentable->title : " پروفایل  ".$comment->commentable->name  }}</a></td>
                                <td class="view-message ">
                                    <a href="{{route('panel.comments.status' ,['id' => $comment->id] )}}"> {{ \App\Utility\Status::getStatus($comment->status) }} </a>
                                </td>
                                <td>
                                    <button class="btn btn-success btn-xs" title="@lang('cms.show')" data-toggle="modal" href="#show{{ $comment->id }}"><i class="icon-eye-open"></i></button>
                                    <button class="btn btn-primary btn-xs" title="@lang('cms.accept-2')" data-toggle="modal" href="#edit{{ $comment->id }}"><i class="icon-hand-up"></i></button>
                                    <button class="btn btn-danger btn-xs" title="@lang('cms.delete')" data-toggle="modal" href="#delete{{ $comment->id }}"><i class="icon-trash "></i></button>

                                </td>
                            </tr>
                        @endforeach
                        </tbody>

                    </table>

                    <span style="margin-right: 45%">{!! $comments->render() !!}</span>

                    <!-- Modal show -->
                    @foreach($comments as $comment)
                        <div class="modal fade "  id="show{{ $comment->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                        <h4 class="modal-title"><span class=""> @lang('cms.comment-number')   {{ $comment->id }}   </span> </h4>
                                    </div>
                                    <div class="modal-body">
                                        <h3>{{ $comment->user->name }} @lang('cms.say') </h3>
                                        <p>

                                            {!! $comment->comment !!}
                                        </p>

                                        <hr>


                                        <span class="label label-default">@if($comment->status=='1') @lang('cms.accept')
                                            @else
                                                @lang('cms.pending')
                                            @endif</span>
                                       <span class="label label-primary"> @lang('cms.date') <?php $v = verta($comment->created_at);

                                            echo $v->format('%d %B %Y H:i');
                                            ?></span>
                                        @if($comment->ip!="")<span class="label label-success">@lang('cms.ip')  {{ $comment->ip }}</span>@endif

                                    </div>



                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                @endforeach
                <!-- /.modal-dialog -->
                </div>
                <!-- /.modal show -->


                <!-- Modal edit -->
                @foreach($comments as $comment)
                    <div class="modal fade" id="edit{{ $comment->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                    <h4 class="modal-title"> @lang('cms.accept-comment') {{$comment->user->name}}</h4></div>
                                <div class="modal-body">

                                    <form class="cmxform form-horizontal tasi-form" id="commentForm" method="post" action="{{ route('comments.update',$comment->id) }}">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="_method" value="PATCH">
                                        <div class="modal-body">
                                            <p>
                                                {{ str_limit($comment->comment,150) }}
                                            </p>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-lg-offset-2 col-lg-10">
                                                <input class="btn btn-danger" type="submit" value="@lang('cms.accept-2')">

                                            </div>
                                        </div>
                                    </form>
                                </div>



                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
            @endforeach
            <!-- /.modal-dialog -->
        </div>
        <!-- /.modal edit -->




        <!-- Modal delete -->
        @foreach($comments as $comment)
            <div class="modal fade" id="delete{{ $comment->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title"> @lang('cms.delete-comment') {{$comment->user->name}}</h4>
                        </div>
                        <div class="modal-body">

                            <p>
                                {!! $comment->content !!}</p>
                        </div>
                        <div class="modal-footer">
                            <form action="{{ route('comments.destroy',$comment->id) }}" method="POST">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="_method" value="DELETE">

                                <input type="submit" name="btndelete" value="@lang('cms.delete')" class="btn btn-danger">
                            </form>
                            <br>
                            <button type="button" class="btn btn-default" data-dismiss="modal">@lang('cms.cancel')</button>
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

