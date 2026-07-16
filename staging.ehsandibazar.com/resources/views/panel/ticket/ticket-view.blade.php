@extends('panel.layout.master')

@section('top-menu')
    @include('panel.layout.partials.topNav')
@endsection

@section('right-menu')
    @include('panel.layout.partials.rightNav')
@endsection

@section('content')
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="col-lg-3 col-md-10 col-sm-8 col-xs-7">
                    <div class="form-group">
                        <div class="form-line">
                            <select name="statusTicket" class="statusTicket" onchange="ticketStatus(this)">
                                @foreach(\App\Utility\TicketType::TicketStatus() as  $index  => $value)
                                    <option value="{{$index}}" {{ $ticket->status == $index ? 'selected' : null }}>{{$value}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                @if($ticket->status != \App\Utility\TicketType::CLOSE)
                    <div class="body">
                        @include('generals.allErrors')
                        @include('generals.sessionMessage')
                        <form method="post" action="{{ route('panel.ticket.answer.sent') }}" class="form-horizontal"
                              enctype="multipart/form-data">
                            @csrf
                            <input value="{{ $ticket->id }}" name="ticket_id" type="hidden">
                            <textarea name="answer" class="form-control input-lg p-text-area ckeditor"></textarea>
                            <input type="file" name="attach">
                            <footer class="panel-footer">
                                @can('panel.ticket.answer.sent')
                                    {{-- button --}}
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-7 ">
                                            <button type="submit" class="btn-hover color-2 pull-left">ارسال
                                            </button>
                                        </div>
                                    </div>
                                @endcan
                                <ul class="nav nav-pills">

                                </ul>
                            </footer>
                        </form>
                    </div>
                @endif

                <div class="body">
                    <div class="table-responsive">
                        <div class="panel-body profile-activity">
                            @if(isset($ticket->answer) && count($ticket->answer) > 0)
                                @foreach($ticket->answer as $key => $answer)
                                    <div class="activity {{ $answer->user->isUser() ? '' : 'alt terques' }} ">
                                    <span>
                                        @if(isset($answer->user->image[0]))
                                            <img src="{{ $answer->user->image[0]->url }}" class="img-ticket">
                                        @else
                                            <span>
                                                <i class="fas fa-user"></i>
                                            </span>
                                        @endif
                                    </span>
                                        <div class="activity-desk">
                                            <div class="panel" style="max-width: 400px;min-width: 400px;">
                                                <div class="panel-body">
                                                    <div class="arrow"></div>
                                                    <i class="fas fa-clock"></i>
                                                    <h4> @php
                                                            $v = verta($answer->created_at);
                                                            echo $v->format('%d %B %Y H:i');
                                                        @endphp</h4>

                                                    <i class="icon-exchange"></i>


                                                    <p>{!! $answer->answer !!}</p>
                                                    @if(isset($answer->files[0]) && !empty($answer->files[0]))
                                                        <a href="{{$answer->files[0]->url}}" class="btn btn-default"
                                                           target="_blank">فایل ضمیمه</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="alert alert-info col-12 border-right-info text-right">
                                    تیکت در حال بررسی میباشد و پاسخی برای آن ثبت نشده است.
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('admin-js')
    <script>
        function ticketStatus(selectObject) {
            $.ajax({
                type: "get",
                url: "{{route('panel.ticket.status')}}",
                data: {
                    status: selectObject.value,
                    ticket: "{{ $ticket->id }}",
                },
                success: function (data) {
                    if (data.status == 200) {
                        sweetAlert(data.msg);
                    }
                },
                error: function (error) {
                    Swal.fire({
                        title: "خطا",
                        text: "لطفا بعدا تلاش فرمایید",
                        icon: "error",
                        button: "تایید",
                    });
                }
            });
        }
    </script>
@endsection
