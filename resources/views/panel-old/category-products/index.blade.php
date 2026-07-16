@extends('panel-old.layout.master')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <section class="panel padding-bottom-panel">
                <br>
                <header class="panel-heading">
                    @lang('cms.header-list-category-product')
                    <a type="button" class="btn btn-xs btn-success top-left" data-toggle="modal"
                       href="{{route('panel.categoryProduct.create')}}">@lang('cms.create-new-item')
                    </a>
                </header>


            </section>
        </div>

        @include('generals.allErrors')
        @include('generals.sessionMessage')

        <div class="container">
            <section class="panel">
                <div class="panel-body">
                    <table class="table table-hover"  id="datatable">
                        <thead>
                        <tr>
                            <th>@lang('cms.num')</th>
                            <th>@lang('cms.title')</th>
                            <th>@lang('cms.category')</th>
                            <th>@lang('cms.picture')</th>
                            <th>@lang('cms.status')</th>
                            <th>@lang('cms.operation')</th>
                        </tr>
                        </thead>

                        <tbody>
                        @if(isset($categoryProduct) && count($categoryProduct) > 0)
                            @foreach($categoryProduct as $val)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ \Illuminate\Support\Str::limit($val->title,30) }}</td>
                                    <td>{{ isset($val->parentCategory->title) && !empty($val->parentCategory->title) ? $val->parentCategory->title : 'دسته بندی اصلی' }}</td>

                                    <td>
                                        @if(isset($val->image[0]) && !empty($val->image[0]))
                                        <img class="img-responsive fit-image img-circle"
                                             src="{{ isset($val->image[0]) && !empty($val->image[0]) ? $val->image[0]->url : null }}"
                                             alt="{{ $val->title }}">
                                            @else
                                        بدون تصویر
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{route('panel.categoryProduct.status' , ['id' => $val->id])}}">{{  \App\Utility\Status::getStatus($val->status)  }}</a>
                                    </td>

                                    <td>
                                        
                                          @can('update')
                                        <a class="btn btn-warning btn-xs" title="@lang('cms.edit')"
                                           href="{{ route('panel.categoryProduct.edit',['id'=>$val->id ]) }}"><i
                                                class="icon-edit "></i></a>
                                                @endcan

                                        <button class="btn btn-info btn-xs" title="@lang('cms.show')"
                                                data-toggle="modal"
                                                href="#show{{$val->id}}"><i class="icon-eye-open "></i></button>
                                                
                                                
                                                  @can('delete')
                                        <button class="btn btn-danger btn-xs" title="@lang('cms.delete')"
                                                data-toggle="modal"
                                                href="#delete{{$val->id}}"><i class="icon-trash "></i></button>
                                                @endcan


                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>

                    </table>

{{--                    @if(isset($categoryProduct) && count($categoryProduct) > 0)--}}
{{--                        <span style="margin-right: 45%">{!! $categoryProduct->render() !!}</span>--}}
{{--                    @endif--}}


                </div>
            </section>
        </div>


        <!-- Modal show -->
        @if(isset($categoryProduct) && count($categoryProduct) > 0)
            @foreach($categoryProduct as $val)

                <div class="modal fade" id="show{{ $val->id }}" tabindex="-1" role="dialog"
                     aria-labelledby="myModalLabel"
                     aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;
                                </button>
                                <h4 class="modal-title">@lang('cms.show-details')</h4>
                            </div>
                            <div class="modal-body text-center">
                                <p>
                                    @lang('cms.show-details-category'){{$val->title}}
                                </p>
                                @if(isset($val->image[0]))
                                    <img src="{{  $val->image[0]->url }}">
                                @else
                                    <h3> بدون تصویر </h3>
                                @endif
                                <hr>

                                <form id="form{{$val->id}}" action="{{route('panel.categoryProduct.isSearchFilter')}}"
                                      method="post">
                                    @csrf
                                    @foreach($val->attributes as $items)
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <div class="col-md-4">
                                                    <p><span>{{ $loop->iteration . ")"  }}</span> {{ $items->name }}
                                                    </p>
                                                </div>
                                                <div class="col-md-4">

                                                    <label
                                                        for="is_filterable{{$loop->iteration}}">@lang('cms.filter')</label>
                                                    <input type="checkbox" name="is_filterable[]"
                                                           id="is_filterable{{$loop->iteration}}"
                                                           value="{{$items->id}}" {{$items->pivot->is_filterable == 1 ? "checked" : null }} >
                                                </div>
                                                <div class="col-md-4">
                                                    <label
                                                        for="is_searchable{{$loop->iteration}}">@lang('cms.label-search')</label>
                                                    <input type="checkbox" name="is_searchable[]"
                                                           value="{{$items->id}}"
                                                           {{$items->pivot->is_searchable == 1 ? "checked" : null }}
                                                           id="is_searchable{{$loop->iteration}}">
                                                </div>
                                                <input type="hidden" name="category_id" value="{{$val->id}}">
                                            </div>
                                        </div>
                                        <hr>
                                    @endforeach
                                </form>

                            </div>

                            <div class="modal-footer">

                                <button type="submit" id="{{$val->id}}"
                                        class="btn btn-default pull-right  saveFilterSearchAble">
                                    @lang('cms.apply')
                                </button>
                                <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">
                                    @lang('cms.cancel')
                                </button>
                            </div>

                            <div id="reload" class="hidden-button">
                                <img src="{{url('admin_theme/img/reload.gif')}}" alt="">
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


    <!-- Modal delete -->
    @if(isset($categoryProduct) && count($categoryProduct) > 0)
        @foreach($categoryProduct as $val)
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
                            <form action="{{ route('panel.categoryProduct.delete' , ['id' => $val->id])  }}"
                                  method="POST">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="_method" value="DELETE">

                                <input type="submit" name="btndelete" value="@lang('cms.delete')"
                                       class="btn btn-danger">
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

@section('admin-js')


    <script type="text/javascript">
        {{-- when checked input more than 1 --}}
        /*  $(document).ready(function () {
              //var n = $("input:checked").length;
              //alert(n);
              var check = 0;

              /!* first in update if checked show button *!/
              var checked =  $('input[type="checkbox"]').prop('checked');
              if(checked){
                  $('.saveFilterSearchAble').css('display', 'block');
              }


              $('input[type="checkbox"]').click(function () {
                  if ($(this).prop("checked") == true) {
                      check += 1;
                      if (check > 0) {
                          $('.saveFilterSearchAble').css('display', 'block');
                      } else {
                          $('.saveFilterSearchAble').css('display', 'none');
                          $('#reload').css('display', 'none');
                          $('.saveFilterSearchAble').attr('disabled', false);
                      }
                  } else if ($(this).prop("checked") == false) {
                      check -= 1;
                      if (check > 0) {
                          $('.saveFilterSearchAble').css('display', 'block');
                      } else {
                          $('.saveFilterSearchAble').css('display', 'none');
                          $('#reload').css('display', 'none');
                          $('.saveFilterSearchAble').attr('disabled', false);
                      }
                  }
              });

          });*/

        /* when clicked submit form */
        $('.saveFilterSearchAble').click(function (e) {
            e.preventDefault();
            var idForm = $(this).attr('id');
            $("#form" + idForm).submit();
        });
    </script>

@endsection

@endsection

