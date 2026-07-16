@extends('panel-old.layout.master')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    @if(isset($findGuarantyGuaranty) && count($findGuarantyGuaranty) > 0)
                        @lang('cms.header-guaranty-edit')
                    @else
                        @lang('cms.header-guaranty-create')
                    @endif
                </header>


                @include('generals.allErrors')
                @include('generals.sessionMessage')


                <div class="panel-body">
                    <div class=" form">

                        @if(isset($findGuaranty) )
                            <form class="cmxform form-horizontal tasi-form" id="commentForm" method="post"
                                  enctype="multipart/form-data"
                                  action="{{route('panel.guaranty.update' , ['id' => $findGuaranty->id])}}">
                                {{method_field("PATCH")}}
                                @else
                                    <form class="cmxform form-horizontal tasi-form" id="commentForm" method="post"
                                          enctype="multipart/form-data"
                                          action="{{route('panel.guaranty.store')}}">

                                        @endif

                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                        <div class="form-group ">
                                            <label for="title"
                                                   class="control-label col-lg-2">@lang('cms.title') </label>
                                            <div class="col-lg-10">
                                                <input class=" form-control"
                                                       value="{{isset($findGuaranty)  ? $findGuaranty->title : null }}"
                                                       id="title" name="title" minlength="2" type="text" required/>
                                            </div>
                                        </div>

                                        <div class="form-group ">
                                            <label for="description"
                                                   class="control-label col-lg-2">@lang('cms.description') </label>
                                            <div class="col-lg-10">
                                                <textarea class="form-control" name="description" id="description"
                                                          cols="30"
                                                          rows="10">{{isset($findGuaranty)  ? $findGuaranty->description : null }}</textarea>
                                            </div>
                                        </div>


                                        {{-- status--}}
                                        <div class="form-group ">
                                            <label for="title"
                                                   class="control-label col-lg-2">@lang('cms.status')</label>
                                            <div class="col-lg-10">
                                                <select name="status" class="form-control select-option" id="">
                                                    @foreach(\App\Utility\Status::Status() as $key => $value)
                                                        <option
                                                            value="{{$key}}" {{ isset($findGuaranty) && $key == $findGuaranty->status ? 'selected' : null }} >{{$value}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            @if(isset($findGuaranty) )
                                                <div class="col-lg-offset-2 col-lg-10">
                                                    <input class="btn btn-warning pull-left" type="submit"
                                                           value="@lang('cms.edit')">
                                                </div>
                                            @else
                                                <div class="col-lg-offset-2 col-lg-10">
                                                    <input class="btn btn-success pull-left" type="submit"
                                                           value="@lang('cms.save')">
                                                </div>
                                            @endif

                                        </div>
                                    </form>

                    </div>

                </div>

            </section>
        </div>
    </div>

@endsection

