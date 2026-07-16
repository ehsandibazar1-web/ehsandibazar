@extends('panel.layout.master')

@section('top-menu')
    @include('panel.layout.partials.topNav')
@stop

@section('right-menu')
    @include('panel.layout.partials.rightNav')
@stop

@section('content')

    <div class="row clearfix">

        <div class="col-lg-12 col-md-12">
            <div class="card">
                @if(!auth()->user()->isAdminOrSuperAdmin())
                    <div class="body">
                        @include('generals.allErrors')
                        @include('generals.sessionMessage')
                        <form method="post" action="{{ route('panel.ticket.sent') }}" class="form-horizontal">
                            @csrf
                            <input type="text" name="subject" class="form-control input-mr-b"
                                   placeholder="موضوع تیکت خود را بنویسید...">


                            <textarea name="body" class="form-control input-lg p-text-area ckeditor"></textarea>
                            <footer class="panel-footer">
                                @can('panel.ticket.sent')
                                    {{-- button --}}
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-7 ">
                                            <button type="submit" class="btn-hover color-1 pull-left">ارسال
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

                        @if(isset($tickets) && count($tickets) > 0)
                            <table class="table table-bordered table-striped table-hover js-basic-example ">
                                <thead>
                                <tr>
                                    <th class="text-center">#</th>
                                    <th class="text-center">ارسال کننده</th>
                                    <th class="text-center">موضوع</th>
                                    <th class="text-center">تاریخ</th>
                                    <th class="text-center">وضعیت</th>
                                    <th class="text-center">اولویت</th>
                                    <th class="text-center">دپارتمان</th>
                                    <th class="text-center">شماره تیکت</th>
                                    <th class="text-center">عملیات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($tickets as $ticket)
                                    <tr>
                                        <td class="text-center"> {{ $loop->iteration  }} </td>
                                        <td class="text-center">{{ $ticket->user->fullName }}</td>
                                        <td class="text-center">{{ $ticket->subject }}</td>

                                        <td class="text-center">
                                            @php
                                                $v = verta($ticket->created_at);
                                                echo $v->format('%d %B %Y H:i');
                                            @endphp
                                        </td>
                                        <td class="text-center">
                                            {{ \App\Utility\TicketType::GetTicketStatus($ticket->status,1) }}
                                        </td>

                                        <td class="text-center">{{ \App\Utility\TicketType::GetTicketPriority($ticket->priority) }}</td>
                                        <td class="text-center">{{ $ticket->departemans->name }}</td>


                                        <td class="text-center">{{ $ticket->tracking_code }}</td>

                                        <td class="text-center">
                                            <button type="button"
                                                    class="btn bg-light-blue btn-circle waves-effect waves-circle waves-float"
                                                    data-toggle="modal" data-target="#detailsModel{{$ticket->id}}">
                                                <i class="material-icons">search</i>
                                            </button>

                                            @can('panel.ticket.delete')
                                                <button type="button"
                                                        class="btn btn-danger btn-circle waves-effect waves-circle waves-float"
                                                        data-toggle="modal" data-target="#deleteModel{{$ticket->id}}">
                                                    <i class="material-icons">delete</i>
                                                </button>
                                            @endcan


                                            @can('panel.ticket.view')
                                                <a
                                                    class="btn btn-default btn-circle waves-effect waves-circle waves-float"
                                                    title="پاسخ به تیکت"
                                                    href="{{ route('panel.ticket.view',$ticket->id) }}">
                                                    <i class="fas fa-reply"></i>
                                                </a>
                                            @endcan
                                        </td>

                                    </tr>

                                    {{-- modal details --}}
                                    <div class="modal fade" id="detailsModel{{$ticket->id}}" tabindex="-1" role="dialog"
                                         aria-labelledby="exampleModalLabel"
                                         aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"
                                                        id="exampleModalLabel">{{ $ticket->subject }}</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <label>متن تیکت :</label>
                                                    <div>{!! $ticket->body !!}</div>
                                                    <span
                                                        class="label label-success">توسط : {{ $ticket->user->fullName }}</span>
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


                                    {{-- modal delete --}}
                                    <div class="modal fade" id="deleteModel{{$ticket->id}}" tabindex="-1"
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
                                                    آیا از حذف تیکت مورد نظر اطمینان دارین؟
                                                </div>
                                                <div class="modal-footer">
                                                    <form action="{{ route('panel.ticket.delete',$ticket->id) }}"
                                                          method="POST">
                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                        <input type="hidden" name="_method" value="DELETE">

                                                        <button type="submit" class="btn btn-danger waves-effect">حذف
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
                            </table>


                        @else

                            <div class="alert alert-info col-12 border-right-info">تیکتی برای نمایش وجود ندارد ، برای
                                ارسال
                                تیکت از طریق فرم بالا اقدام نمایید
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>

        @endsection

        @section('admin-js')
            <script src=" {{ url('admin/assets/js/table.min.js')  }} "></script>
            <script src=" {{ url('admin/assets/js/pages/tables/jquery-datatable.js')  }} "></script>--}
@endsection
