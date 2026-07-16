@extends('panel.layout.master')

@section('top-menu')
    @include('panel.layout.partials.topNav')
@stop

@section('right-menu')
    @include('panel.layout.partials.rightNav')
@stop

@section('content')

    <!-- Basic Examples -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        {{ $title ?? "" }} <b>{{ $user->fullName }}</b>
                        <div class="pull-left margin-5">
                            @can('panel.digitalProduct.add')
                                <a href="{{ route('panel.digitalProduct.add')  }}"
                                   class="btn btn-outline-default btn-border-radius"> ایجاد سابقه خرید </a>
                            @endcan
                            @can('panel.digitalProduct.delete')
                                    <button class="btn btn-danger btn-border-radius delete_all"
                                            data-url="{{ route('panel.digitalProduct.delete')  }}">
                                        حذف آیتم های انتخابی
                                    </button>
                            @endcan
                                @can('panel.digitalProduct.index')
                                    <a href="{{ route('panel.digitalProduct.index')  }}"
                                       class="btn btn-outline-success btn-border-radius"> فهرست </a>
                                @endcan
                        </div>
                    </h2>

                    <ul class="header-dropdown m-r--5">
                        <li class="dropdown">
                            <a href="#" onClick="return false;" class="dropdown-toggle" data-toggle="dropdown"
                               role="button"
                               aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons">more_vert</i>
                            </a>
                            <ul class="dropdown-menu pull-right">
                                <li>
                                    <a href="{{ route('panel.dashboard.index')  }}">داشبورد</a>
                                </li>
                                @can('panel.digitalProduct.add')
                                    <li>
                                        <a href="{{ route('panel.digitalProduct.add')  }}" style="font-size: 10px">
                                            ایجاد سابقه خرید
                                        </a>
                                    </li>
                                @endcan
                                @can('panel.digitalProduct.delete')
                                    <li>
                                        <button class="delete_all" href="{{ route('panel.digitalProduct.delete')  }}"
                                                style="font-size: 10px">
                                            حذف آیتم های انتخابی
                                        </button>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    </ul>

                </div>
                <div class="body">
                    <div class="table-responsive">
                        @if (isset($productions) && count($productions) > 0)
                            <table class="table table-bordered table-striped table-hover js-basic-example dataTable">
                                <thead>
                                <tr>
                                    <th class="text-center">
                                        <label class="form-check-label">
                                            <input type="checkbox" id="master">
                                            <span class="form-check-sign"></span>
                                        </label>
                                        @lang('cms.num')</th>
                                    <th class="text-center">محصول</th>
                                    {{-- <th class="text-center">@lang('cms.operation')</th>--}}
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($productions as $val)
                                    <tr>

                                        <td class="text-center">
                                            <label class="form-check-label">
                                                <input type="checkbox" class="sub_chk" data-id="{{$val->id}}"> &nbsp;
                                                <span class="form-check-sign"></span>
                                            </label>
                                        {{ $loop->iteration }}
                                        <td class="text-center"><a href="{{ $val->path() }}">{{ $val->title }}</a></td>
                                        {{--                                <td>--}}
                                        {{--                                    <a class="btn btn-success btn-xs" title="@lang('cms.show')" target="_blank"--}}
                                        {{--                                            href="{{ route('panel.digitalProduct.show',$val) }}"><i class="icon-eye-open"></i></a>--}}

                                        {{--                                    <a href="{{ url('digital-product',$val->id) }}" class="btn btn-danger btn-sm"--}}
                                        {{--                                       data-tr="tr_{{$val->id}}"--}}
                                        {{--                                       data-toggle="confirmation"--}}
                                        {{--                                       data-btn-ok-label="Delete" data-btn-ok-icon="fa fa-remove"--}}
                                        {{--                                       data-btn-ok-class="btn btn-sm btn-danger"--}}
                                        {{--                                       data-btn-cancel-label="Cancel"--}}
                                        {{--                                       data-btn-cancel-icon="fa fa-chevron-circle-left"--}}
                                        {{--                                       data-btn-cancel-class="btn btn-sm btn-default"--}}
                                        {{--                                       data-title="Are you sure you want to delete ?"--}}
                                        {{--                                       data-placement="left" data-singleton="true">--}}
                                        {{--                                        حذف--}}
                                        {{--                                    </a>--}}
                                        {{--                                </td>--}}
                                    </tr>
                                @endforeach

                                </tbody>
                                <tfoot>
                                <tr>
                                    <th class="text-center">
                                        <input type="checkbox" id="master">
                                        @lang('cms.num')</th>
                                    <th class="text-center">محصول</th>
                                    {{-- <th class="text-center">@lang('cms.operation')</th>--}}
                                </tr>
                                </tfoot>

                            </table>

                        @else
                            <p class="alert alert-info text-center"> رکوردی یافت نشد. </p>
                        @endif
                        <div class="container">
                            <div class="pull-left">
                                {{--                                @if(isset($productions) && $productions->count() > 0)--}}
                                {{--                                    <span style="margin-right: 45%">{!! $productions->render() !!}</span>--}}
                                {{--                                @endif--}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Basic Examples -->


@stop
@section('admin-js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-confirmation/1.0.5/bootstrap-confirmation.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {

            $('#master').on('click', function (e) {
                if ($(this).is(':checked', true)) {
                    $(".sub_chk").prop('checked', true);
                } else {
                    $(".sub_chk").prop('checked', false);
                }
            });


            $('.delete_all').on('click', function (e) {

                var allVals = [];
                $(".sub_chk:checked").each(function () {
                    allVals.push($(this).attr('data-id'));
                });


                if (allVals.length <= 0) {
                    alert("لطفا حداقل یک آیتم را انتخاب نمایید");
                } else {


                    var check = confirm("آیا مطمئن هستید که می خواهید این سطر را حذف کنید؟");
                    if (check == true) {
                        var join_selected_values = allVals.join(",");

                        $.ajax({
                            url: $(this).data('url'),
                            type: 'DELETE',
                                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            data: {
                                product: join_selected_values,
                                userId: '{{$user->id}}'
                            },
                            success: function (data) {
                                if (data['success']) {
                                    $(".sub_chk:checked").each(function () {
                                        $(this).parents("tr").remove();
                                    });
                                    alert(data['success']);
                                } else if (data['error']) {
                                    alert(data['error']);
                                } else {
                                    alert('Whoops Something went wrong!!');
                                }
                            },
                            error: function (data) {
                                alert(data.responseText);
                            }
                        });


                        $.each(allVals, function (index, value) {
                            $('table tr').filter("[data-row-id='" + value + "']").remove();
                        });
                    }
                }
            });


            $('[data-toggle=confirmation]').confirmation({
                rootSelector: '[data-toggle=confirmation]',
                onConfirm: function (event, element) {
                    element.trigger('confirm');
                }
            });


        });
    </script>
@endsection

