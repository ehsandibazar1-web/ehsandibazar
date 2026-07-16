@extends('panel-old.layout.master')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <section class="panel">
                <header class="panel-heading">
                    @if(isset($findGiftGift) && count($findGiftGift) > 0)
                       @lang('cms.header-gift-edit')
                    @else
                       @lang('cms.header-git-create')
                    @endif
                </header>


                @include('generals.allErrors')
                @include('generals.sessionMessage')


                <div class="panel-body">
                    <div class=" form">

                        @if(isset($findGift) )
                            <form class="cmxform form-horizontal tasi-form" id="commentForm" method="post"
                                  enctype="multipart/form-data"
                                  action="{{route('panel.gift.update' , ['id' => $findGift->id])}}">
                                {{method_field("PATCH")}}
                                @else
                                    <form class="cmxform form-horizontal tasi-form" id="commentForm" method="post"
                                          enctype="multipart/form-data"
                                          action="{{route('panel.gift.store')}}">

                                        @endif

                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                        <div class="form-group ">
                                            <label for="name" class="control-label col-lg-2">@lang('cms.name') </label>
                                            <div class="col-lg-10">
                                                <input class=" form-control"
                                                       value="{{isset($findGift)  ? $findGift->name : null }}"
                                                       id="name"  name="name" minlength="2" type="text" />
                                            </div>
                                        </div>


                                        {{-- image --}}
                                        <div class="form-group">
                                            <label for="images" class="control-label col-lg-2">
                                                @lang('cms.featuring-image')
                                            </label>
                                            <div class="col-md-10">
                                                <div class="input-group">
                                                  <span class="input-group-btn">
                                                    <a id="lfm" data-input="thumbnail2" data-preview="holder2"
                                                       class="btn btn-primary">
                                                      <i class="fa fa-picture-o"></i>
                                                        @lang('cms.choose')
                                                    </a>
                                                  </span>

                                                    <input id="thumbnail2" class="form-control" type="text"
                                                           value="{{isset($findGift) && !empty($findGift) && count($findGift->image) > 0 && file_exists(base_path().$findGift->image[0]->url) ? $findGift->image[0]->url : old('filepath')[0] }}"
                                                           name="filepath">
                                                </div>
                                            </div>

                                            <br>
                                            <br>
                                            <br>

                                            @if (isset($findGift) && !empty($findGift))
                                                @if(!empty($findGift->image) && isset($findGift->image[0]->url) && !empty($findGift->image[0]->url))
                                                    @if(file_exists(base_path().$findGift->image[0]->url))
                                                        <div class="row">
                                                            <div class="col-md-12 text-center">
                                                                <img id="holder2" src="{{url($findGift->image[0]->url)}}"
                                                                     style="margin-top:15px;max-height:100px;">
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="col-md-12 text-center">
                                                        <img width="50" src="{{url('general/img/404-error.png')}}" alt="inten">
                                                        </div>
                                                    @endif
                                                @else
                                                    <div class="col-md-12 text-center">
                                                    <img width="50" src="{{url('general/img/404-error.png')}}" alt="inten">
                                                    </div>
                                                @endif
                                            @endif
                                            <div class="col-md-12 text-center">
                                            <img id="holder2" style="margin-top:15px;max-height:100px;">
                                            </div>
                                        </div>


                                        <div class="form-group ">
                                            <label for="cname" class="control-label col-lg-2 ">@lang('cms.products')</label>
                                            <div class="col-lg-10">
                                                <select name="product_id" class="form-control col-lg-4 select-option">
                                                    @if(isset($allProduct) && !empty($allProduct))
                                                        @foreach($allProduct as $item)
                                                            <option value="{{$item->id}}"
                                                            {{ isset($findGift) && !empty($findGift) && $item->id == $findGift->product_id ? "selected" : null  }}
                                                            >{{$item->title}}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>

                                        {{-- score --}}
                                        <div class="form-group">
                                                <label for="" class="control-label col-lg-2"> @lang('cms.score') </label>
                                                <div class="col-lg-10">
                                                    <input name="score" class="form-control" value="{{ isset($findGift) && !empty($findGift) ? $findGift->score  : null}}" type="number" min="0" >
                                                </div>
                                        </div>


                                        {{-- lang --}}
                                        <div class="form-group">
                                            <label for="" class="col-lg-2"> @lang('cms.lang') </label>
                                            <div class="col-lg-10">
                                                <select name="lang" id="lang" class="form-control select-option">
                                                    @foreach(\App\Utility\lang::langEach() as $keyLang =>  $itemLang)
                                                        <option value="{{$keyLang}}" {{isset($findGift) && !empty($findGift) && $findGift->lang == $itemLang ? "selected" : null }} >{{$itemLang}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>


                                        {{-- status--}}
                                        <div class="form-group ">
                                            <label for="title" class="control-label col-lg-2">
                                                @lang('cms.status')
                                            </label>
                                            <div class="col-lg-10">
                                                <select name="status" class="form-control select-option" id="">
                                                    @foreach(\App\Utility\Status::Status() as $key => $value)
                                                        <option
                                                            value="{{$key}}" {{ isset($findGift) && !empty($findGift) && $key == $findGift->status ? 'selected' : null }} >{{$value}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>



                                        <div class="form-group">
                                            @if(isset($findGift) )
                                                <div class="col-lg-offset-2 col-lg-10">
                                                    <input class="btn btn-warning pull-left" type="submit" value="@lang('cms.edit')">
                                                </div>
                                            @else
                                                <div class="col-lg-offset-2 col-lg-10">
                                                    <input class="btn btn-success pull-left" type="submit" value="@lang('cms.save')">
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

