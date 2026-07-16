@extends('panel-old.layout.master')

@section('content')
    <div class="row">

        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    @lang('cms.header-list-user')

                    <div class="btn-group">
                        @can('acl')
                            <a class="btn btn-danger" href="{{ route('roles.index') }}">@lang('cms.level-access')</a>
                            @endcan
                      
                         @can('user-level')  <a class="btn btn-success" href="{{ route('level.index') }}">@lang('cms.user-manage')</a> @endcan
                         @can('create-user') <a class="btn btn-warning"
                           href="{{ route('panel.users.create') }}">@lang('cms.create-new-user-2')</a>
                           @endcan
                              @can('users-export') <a class="btn btn-default" href="{{ route('panel.user.export') }}">
                                <i class="icon-file"></i>
                                خروجی اکسل</a>
                                @endcan
                    </div>

                </header>


                <div class="panel-body">

                    <form action="{{ route('panel.user.search') }}" method="get">

                        <div class="form-group col-sm-2">
                            <input type="text" class="form-control" id="name" name="name" placeholder="نام و نام خانوادگی...">
                        </div>


                        <div class="form-group col-sm-2">
                            <input type="email" class="form-control" id="email" name="email" placeholder="ایمیل...">
                        </div>

                        <div class="form-group col-sm-2">
                            <input type="text" class="form-control" id="mobile" name="mobile" placeholder="موبایل...">
                        </div>

                        <div class="form-group col-sm-2">
                            <input type="submit" value="جستجو" class="btn btn-primary">
                        </div>

                    </form>

                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>@lang('cms.num')</th>
                            <th>@lang('cms.name')</th>
                            <th>@lang('cms.family')</th>
                            <th>@lang('cms.mobile')</th>
                            <th>@lang('cms.level')</th>
                            <th>@lang('cms.block')</th>
                            <th>@lang('cms.status')</th>
                            <th>@lang('cms.operation')</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($user as $val)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $val->name }}</td>
                                <td>{{ $val->family }}</td>
                                <td>{{$val->mobile}}</td>
                                <td>{{ \App\Utility\Level::getLevel($val->level) }}</td>
                                <td>
                                    <a href="{{ route('user.block.change', ['id' => $val->id])  }}"
                                       title="@lang('cms.block')"> {{ \App\Utility\Status::getBlock($val->block) }} </a>
                                </td>
                                <td>
                                    <a href="{{ route('user.status.change', ['id' => $val->id])  }}"
                                       title="@lang('cms.status')"> {{ \App\Utility\Status::getStatus($val->active) }} </a>
                                </td>


                                <td>
                                    <button class="btn btn-success btn-xs" title="@lang('cms.show')" data-toggle="modal"
                                            href="#show{{ $val->id }}"><i class="icon-eye-open"></i></button>
                                             @can('update')
                                    <button class="btn btn-warning btn-xs" title="@lang('cms.edit')" data-toggle="modal"
                                            href="#edit{{ $val->id }}"><i class="icon-pencil"></i></button>
                                            @endcan
                                        @can('delete')
                                    <button class="btn btn-danger btn-xs" title="@lang('cms.delete')"
                                            data-toggle="modal" href="#delete{{ $val->id }}"><i class="icon-trash "></i>
                                    </button>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                        </tbody>

                    </table>

               <span style="margin-right: 45%">
                   {{ $user->appends(request()->query())->links() }}
               </span>

                    <!-- Modal show -->
                    @foreach($user as $val)
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
                                        <p>

                                            ایمیل : {!! $val->email !!}
                                            <br>
                                            موبایل :{{ $val->mobile }}
                                            <br>
                                            تلفن :{{ $val->tell }}
                                            @if($val->isColleague())
                                                <br>
                                                کد ملی : {{ $val->national_code }}
                                                <br>
                                                کد اقتصادی : {{ $val->economic_code }}
                                                <br>
                                                آدرس : {{ $val->full_address }}
                                                <br>
                                            @endif
                                        </p>


                                        <a class="btn btn-xs btn-info" target="_blank" href="">تاریخ عضویت
                                            :<?php $v = verta($val->created_at);

                                            echo $v->format('%d %B %Y H:i');
                                            ?></a>


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
                @foreach($user as $val)
                    <div class="modal fade" id="edit{{ $val->id }}" tabindex="-1" role="dialog"
                         aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                        &times;
                                    </button>
                                    <h4 class="modal-title">@lang('cms.edit-user-2')</h4></div>
                                <div class="modal-body">
                                    @include('generals.allErrors')
                                    <form class="cmxform form-horizontal tasi-form" id="commentForm" method="post"
                                          action="{{ route('panel.users.update'  , ['id' => $val->id]) }}">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="_method" value="PATCH">
                                        <div class="form-group ">
                                            <label for="cname" class="control-label col-lg-2">@lang('cms.name') </label>
                                            <div class="col-lg-10">
                                                <input class=" form-control" value="{{$val->name}}" id="cname"
                                                       name="name" minlength="2" type="text" required/>
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <label for="cname"
                                                   class="control-label col-lg-2">@lang('cms.family') </label>
                                            <div class="col-lg-10">
                                                <input class=" form-control" value="{{$val->family}}" id="cname"
                                                       name="family" type="text"/>
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <label for="mobile"
                                                   class="control-label col-lg-2">موبایل</label>
                                            <div class="col-lg-10">
                                                <input class=" form-control" value="{{$val->mobile}}" id="mobile"
                                                       name="mobile" type="number"/>
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <label for="cname"
                                                   class="control-label col-lg-2">@lang('cms.email') </label>
                                            <div class="col-lg-10">
                                                <input class=" form-control" value="{{$val->email}}" id="cname"
                                                       name="email" type="email"/>
                                            </div>
                                        </div>

                                        @if($val->isColleague())
                                            <div class="form-group">
                                                <label for="discount_percent"
                                                       class="control-label col-lg-2">مقدار تخفیف</label>
                                                <div class="col-lg-10">
                                                    <input class="form-control" value="{{$val->discount_percent}}"
                                                           id="discount_percent"
                                                           name="discount_percent" type="number"/>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="form-group ">
                                            <label for="cname"
                                                   class="control-label col-lg-2"> @lang('cms.type') </label>
                                            <div class="col-lg-10">
                                                <select class="form-control select-option" name="level">
                                                    <option value="">@lang('cms.choose-type-of-user')</option>
                                                    @foreach(\App\Utility\Level::AllLevelEach() as $key => $value)
                                                        <option
                                                            value="{{ $key  }}" {{ $key == $val->level ? 'selected' : null }} >{{ $value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <div class="col-lg-offset-2 col-lg-10">
                                                <input class="btn btn-danger" type="submit" value="@lang('cms.edit')">

                                            </div>
                                        </div>
                                    </form>
                                </div>


                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
        </div>
        <!-- /.modal edit -->
    @endforeach


    <!-- Modal delete -->
        @foreach($user as $val)
            <div class="modal fade" id="delete{{ $val->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title"> @lang('cms.delete-user-by-email') {{$val->email}}</h4>
                        </div>
                        <div class="modal-body">

                            <h3>{{$val->name}}</h3>
                            <p>
                                {{$val->email}}</p>
                        </div>
                        <div class="modal-footer">
                            <form action="{{ route('panel.users.destroy'  , ['id' => $val->id]) }}" method="post">
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
            <!-- /.modal-dialog -->
    </div>
    <!-- /.modal delete -->
    @endforeach

    </div>

    </section>
    </div>
    </div>

@endsection
