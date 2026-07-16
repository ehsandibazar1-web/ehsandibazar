@extends('panel-old.layout.master')

@section('content')


    <div class="row">
        <div class="col-lg-12">
            <section class="panel padding-bottom-panel">
                <br>
                <header class="panel-heading">
                    @lang('cms.list-category-attribute-group')
                    <a type="button" class="btn btn-xs btn-success " data-toggle="modal"
                       href="{{route('panel.attributeGroup.create')}}">@lang('cms.create-new-item')
                    </a>
                </header>


            </section>
        </div>

        @include('generals.allErrors')
        @include('generals.sessionMessage')

        <div class="container">
            <section class="panel">
                <div class="panel-body">

                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>@lang('cms.num')</th>
                            <th>@lang('cms.title')</th>
                            <th>@lang('cms.label')</th>
                            <th>@lang('cms.status')</th>
                            <th>@lang('cms.operation')</th>
                        </tr>
                        </thead>

                        <tbody>
                        @if(isset($attributeGroup) && count($attributeGroup) > 0)
                            @foreach($attributeGroup as $val)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ str_limit($val->name , 50) }}</td>
                                    <td> {{ str_limit($val->label , 50 ) }} </td>
                                    <td>
                                        <a href="@can('status') {{route('panel.attributeGroup.status' , ['id' => $val->id])}} @endcan">{{  \App\Utility\Status::getStatus($val->status)  }}</a>
                                    </td>

                                    <td>

                                        @can('edit')
                                            <a class="btn btn-warning btn-xs" title="@lang('cms.edit')"
                                               href="{{ route('panel.attributeGroup.edit',['id'=>$val->id ]) }}"><i
                                                    class="icon-edit "></i></a>
                                        @endcan



                                        @if(in_array($val->id,$getAttributeGroupId))
                                            <button class="btn btn-danger btn-xs" title="هشدار" data-toggle="modal"
                                                    href="#show{{$val->id}}"><i class="icon-trash "></i></button>
                                        @else
                                            @can('delete')
                                                <button class="btn btn-danger btn-xs" title="حذف" data-toggle="modal"
                                                        href="#delete{{$val->id}}"><i class="icon-trash "></i></button>
                                            @endcan
                                        @endif


                                        {{--                                        @if(in_array($val->id,$getAttributeGroupId))--}}
                                        {{--                                           --}}
                                        {{--                                        @else--}}
                                        {{--                                          --}}
                                        {{--                                        @endif--}}

                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>

                    </table>

                    @if(isset($attributeGroup) && count($attributeGroup) > 0)
                        <span style="margin-right: 45%">{!! $attributeGroup->render() !!}</span>
                    @endif


                </div>
            </section>
        </div>


        <!-- Modal delete -->
        @if(isset($attributeGroup) && count($attributeGroup) > 0)
            @foreach($attributeGroup as $val)
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
                                <form action="{{ route('panel.attributeGroup.delete' , ['id' => $val->id])  }}"
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



    <!-- Modal show -->
    @if(isset($attributeGroup) && count($attributeGroup) > 0)
        @foreach($attributeGroup as $val)
            <div class="modal fade" id="show{{ $val->id }}" tabindex="-1" role="dialog"
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
                            <p class="text-center"> دسته بندی های زیر به {{ $val->name  }} وصل می باشند , ابتدا دسته
                                بندی های زیر را ویرایش کرده و به دسته بندی ویژگی های دیگر منتقل کنید </p>
                            <hr>
                            <h5 class="cat-product"> دسته بندی محصولات :
                                <img src="{{url('admin_theme/img/list.png')}}" class="img-category" alt="category">
                            </h5>

                            @php
                                $arrayProduct_id = [];
                            @endphp
                            @foreach($val->attributes as $itemAttribute)
                                @foreach($itemAttribute->categoryProducts as $itemCategoryProduct)
                                    @if(!in_array($itemCategoryProduct->id,$arrayProduct_id))

                                        <p> {{ $loop->iteration }} )  <span> {{ $itemCategoryProduct->title  }} </span> </p>
                                    @endif
                                    @php
                                        $arrayProduct_id [] = $itemCategoryProduct->id;
                                    @endphp
                                @endforeach
                            @endforeach

                        </div>
                        <div class="modal-footer">
                            <form action="{{ route('panel.attributeGroup.delete' , ['id' => $val->id])  }}"
                                  method="POST">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="_method" value="DELETE">

                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">
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



@endsection
