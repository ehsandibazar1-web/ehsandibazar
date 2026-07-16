@extends('panel-old.layout.master')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    مزایده های برگزار شده
                </header>
            </section>


            @include('generals.allErrors')
            @include('generals.sessionMessage')

            <div class="container">
                <section class="panel">
                    <div class="panel-body">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>@lang('cms.num')</th>
                                @if(auth()->user()->isSuperAdminOrAdmin())<th>کاربر</th>@endif
                                <th>مزایده</th>
                                <th>برنده/بازنده</th>
                                <th>تاریخ</th>
                            </tr>
                            </thead>

                            <tbody>
                            @if(isset($auctions) && !empty($auctions))
                                @foreach($auctions as $val)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        @if(auth()->user()->isSuperAdminOrAdmin())<td>{{ $val->user->name." ".$val->user->family }}</td>@endif
                                        <td><a href="{{ $val->auction->product->path() }}" target="_blank">{{ $val->auction->product->title }}</a></td>
                                        <td>{!! $val->type == 1 ? '<span class="btn btn-success">برنده</span>' : '<span class="btn btn-danger">بازنده</span>' !!}</td>
                                        <td>{{ $val->created_at }}</td>
                                    </tr>

                                @endforeach
                            @endif
                            </tbody>

                        </table>

                        @if(isset($auctions) && count($auctions) > 0)
                            <span style="margin-right: 45%">{!! $auctions->render() !!}</span>
                        @endif
                    </div>
                </section>


                <!-- Modal show -->
                @if(isset($auctions) && count($auctions) > 0)
                    @foreach($auctions as $val)
                        <div class="modal fade" id="show{{ $val->id }}" tabindex="-1" role="dialog"
                             aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog screens">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close close-white" data-dismiss="modal"
                                                aria-hidden="true">
                                            &times;
                                        </button>
                                        <h4 class="modal-title">@lang('cms.show-item')</h4></div>
                                    <div class="modal-body">
                                        <p> @lang('cms.description') </p>
                                        {!!  $val->body  !!}
                                        <hr>
                                        <p>@lang('cms.count-view')</p>

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
        </div>
    </div>
@endsection
