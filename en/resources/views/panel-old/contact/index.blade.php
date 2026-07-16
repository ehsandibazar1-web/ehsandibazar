@extends('panel-old.layout.master')
{{--@section('title')
    مدیریت | تماس با ما
@endsection--}}
@section('content')
    <div class="row">

        <div class="col-lg-12">
            <section class="panel">

                <div class="panel-body">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>@lang('cms.num')</th>
                            <th>@lang('cms.name')</th>
                            <th>@lang('cms.email')</th>
                            <th>@lang('cms.status')</th>
                            <th>@lang('cms.date')</th>
                            <th>@lang('cms.operation')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($contact as $val)

                            <tr class="unread">
                                <td class="inbox-small-cells">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="view-message  dont-show">{{ $val->name }}</td>
                                <td class="view-message ">{{ $val->email }}</td>
                                <td class="view-message">
                                    <a href="{{route('panel.contact.status' ,['id' => $val->id] )}}"> {{ \App\Utility\Status::getStatus($val->status) }} </a>
                                </td>
                                <td class="view-message  text-right"><?php $v = verta($val->created_at);

                                    echo $v->format('%d %B %Y H:i');
                                    ?></td>

                                <td>
                                    @if(!empty($val->user_id) && !empty($val->email))
                                        <a class="btn btn-warning btn-xs" title="ارسال ایمیل" target="_blank"
                                           href="{{ route('panel.newsLatter.sendsEmail',$val->user_id) }}"><i
                                                    class="icon-envelope-alt"></i></a>
                                    @endif
                                    <button class="btn btn-success btn-xs" title="@lang('cms.show')" data-toggle="modal"
                                            href="#show{{ $val->id }}"><i class="icon-eye-open"></i></button>
                                    <button class="btn btn-danger btn-xs" title="@lang('cms.delete')"
                                            data-toggle="modal"
                                            href="#delete{{ $val->id }}"><i class="icon-trash "></i></button>
                                </td>

                            </tr>


                        @endforeach
                        </tbody>
                    </table>
                    <span style="margin-right: 45%">{!! $contact->render() !!}</span>

                    <!-- Modal show -->
                    @foreach($contact as $val)
                        <div class="modal fade" id="show{{ $val->id }}" tabindex="-1" role="dialog"
                             aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                            &times;
                                        </button>
                                        <h4 class="modal-title"><span
                                                    class="">@lang('cms.show-message') {{ $val->name }} </span></h4>
                                    </div>
                                    <div class="modal-body">
                                        <p>
                                            {{ $val->body }}
                                        </p>

                                        <hr>
                                        <span class="label label-default"><?php $v = verta($val->created_at);

                                            echo $v->format('%d %B %Y');
                                            ?></span>
                                        <span class="label label-primary">@lang('cms.email') : {{ $val->email }}</span>
                                        <span class="label label-success">@lang('cms.ip') : {{ $val->ip }}</span>
                                    </div>


                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                @endforeach
                <!-- /.modal-dialog -->
                </div>
                <!-- /.modal show -->

        </div>
        <!-- /.modal edit -->

        <!-- Modal delete -->
        @foreach($contact as $val)
            <div class="modal fade" id="delete{{ $val->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title">@lang('cms.alert')</h4>
                        </div>
                        <div class="modal-body">
                            <p> @lang('cms.question-delete') </p>
                            <p>
                                @lang('cms.content-message')
                                <br>
                                {{ str_limit($val->body,150) }}
                            </p>
                        </div>
                        <div class="modal-footer">
                            <form action="{{ route('contact.destroy',$val->id) }}" method="POST">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="_method" value="DELETE">

                                <input type="submit" name="btndelete" value="@lang('cms.delete')"
                                       class="btn btn-danger">
                                <button type="button" class="btn btn-default pull-right"
                                        data-dismiss="modal">@lang('cms.cancel')
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
