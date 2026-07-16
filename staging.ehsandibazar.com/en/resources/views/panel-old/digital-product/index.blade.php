@extends('panel-old.layout.master')

@section('content')
    <div class="row">

        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                 محصولات دیجیتال خریداری شده
                    <div class="btn-group">
                            <a class="btn btn-danger" href="{{ route('panel.digitalProduct.add') }}">افزودن</a>
                    </div>
                </header>
                <div class="panel-body">

                    <table class="table table-hover" id="datatable">
                        <thead>
                        <tr>
                            <th>@lang('cms.num')</th>
                            <th>@lang('cms.name')</th>
                            <th>@lang('cms.family')</th>
                            <th>@lang('cms.mobile')</th>
                            <th>@lang('cms.operation')</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($users as $val)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $val->name }}</td>
                                <td>{{ $val->family }}</td>
                                <td>{{$val->mobile}}</td>

                                <td>
                                    <a class="btn btn-success btn-xs" title="@lang('cms.show')" target="_blank"
                                            href="{{ route('panel.digitalProduct.show',$val) }}"><i class="icon-eye-open"></i></a>

                                </td>
                            </tr>
                        @endforeach
                        </tbody>

                    </table>


                    @foreach($users as $val)
                        <div class="modal fade " id="show{{ $val->id }}" tabindex="-1" role="dialog"
                             aria-labelledby="myModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                            &times;
                                        </button>
                                        <h4 class="modal-title"><span class="">  @lang('cms.show-details')  </span></h4>
                                    </div>
                                    <div class="modal-body">
                                        <h3>{{$val->name}} {{$val->family}}</h3>
                                        <hr>
                                        <div class="row">
                                            <div class="col-12">
                                                <h4>محصولات دیجیتال خریداری شده :‌</h4>
                                                <ul>
                                                    @foreach($val->production as $product)
                                                        <li style="padding-top: 10px">
                                                            {{$loop->iteration}}) {{ $product->title }}
                                                            <a class="btn btn-danger btn-xs" title="@lang('cms.delete')"
                                                                    data-toggle="modal" href="{{ route('panel.digitalProduct.delete',[$val,$product]) }}"><i class="icon-trash "></i>
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                        <hr>
                                        <p>
                                            <br>
                                            موبایل :{{ $val->mobile }}
                                            <br>

                                        </p>


                                        <a class="btn btn-xs btn-info" target="_blank" href="">تاریخ عضویت
                                            : {{ verta($val->created_at)->format('%d %B %Y H:i') }}</a>


                                    </div>


                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <div class="modal fade" id="delete{{ $val->id }}" tabindex="-1" role="dialog"
                             aria-labelledby="myModalLabel"
                             aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                            &times;
                                        </button>
                                        <h4 class="modal-title"> @lang('cms.delete-user-by-email') {{$val->email}}</h4>
                                    </div>
                                    <div class="modal-body">

                                        <h3>{{$val->name}}</h3>
                                        <p>
                                            {{$val->email}}</p>
                                    </div>
                                    <div class="modal-footer">
                                        <form action="{{ route('panel.users.destroy'  , ['id' => $val->id]) }}"
                                              method="post">
                                            {{ method_field('delete') }}
                                            {{ csrf_field() }}

                                            <input type="submit" name="btndelete" value="@lang('cms.delete')"
                                                   class="btn btn-danger pull-left">
                                        </form>
                                        <button type="button" class="btn btn-default pull-right"
                                                data-dismiss="modal">@lang('cms.cancel')</button>
                                    </div>


                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                    @endforeach

                </div>
            </section>
        </div>
    </div>

@endsection
