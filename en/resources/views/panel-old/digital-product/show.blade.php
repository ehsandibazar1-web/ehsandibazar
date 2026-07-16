@extends('panel-old.layout.master')
@section('admin-js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-confirmation/1.0.5/bootstrap-confirmation.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {

            $('#master').on('click', function(e) {
                if($(this).is(':checked',true))
                {
                    $(".sub_chk").prop('checked', true);
                } else {
                    $(".sub_chk").prop('checked',false);
                }
            });


            $('.delete_all').on('click', function(e) {

                var allVals = [];
                $(".sub_chk:checked").each(function() {
                    allVals.push($(this).attr('data-id'));
                });


                if(allVals.length <=0)
                {
                    alert("لطفا حداقل یک آیتم را انتخاب نمایید");
                }  else {


                    var check = confirm("آیا مطمئن هستید که می خواهید این سطر را حذف کنید؟");
                    if(check == true){
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
                                    $(".sub_chk:checked").each(function() {
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


                        $.each(allVals, function( index, value ) {
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
@section('content')
    <div class="row">

        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                  {{ $user->name." ".$user->family }}محصولات دیجیتال خریداری شده
                    <div class="btn-group">
                            <a class="btn btn-success" href="{{ route('panel.digitalProduct.add') }}">افزودن</a>
                        <button  class="btn btn-danger delete_all "
                                 data-url="{{ route('panel.digitalProduct.delete') }}">حذف آیتم های انتخابی</button>
                    </div>
                </header>
                <div class="panel-body">
                    <table class="table table-hover" id="datatable">
                        <thead>
                        <tr>
                            <th>
                                <input type="checkbox" id="master">
                                @lang('cms.num')</th>
                            <th>محصول</th>
{{--                            <th>@lang('cms.operation')</th>--}}
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($productions as $val)
                            <tr>

                                <td>
                                    <input type="checkbox" class="sub_chk" data-id="{{$val->id}}"> &nbsp;
                                    {{ $loop->iteration }}
                                </td>
                                <td><a href="{{ $val->path() }}">{{ $val->title }}</a></td>
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

                    </table>


                </div>
            </section>
        </div>
    </div>

@endsection
