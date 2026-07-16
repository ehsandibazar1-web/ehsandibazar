@extends('panel-old.layout.master')
@section('title')
     علاقه مندی ها
@endsection
@section('content')
    <div class="row">

        <div class="col-lg-12">
            <section class="panel">

                <header class="panel-heading " style="border:none;border-bottom:1px solid #eff2f7;overflow: hidden">
                    <div class="col-lg-12">علاقه مندی های شما</div>
                </header>

                <div style="clear:both"></div>
                <div class="container row">
                    <div class="col-md-12">
                        @if($favorites->count() > 0)

                            <br>
                            <div class="row product-list">
                                @foreach($favorites as $product)
                                    <div class="col-md-3 favorite-price">
                                        <section class="card">
                                            <div class="pro-img-box">
                                                @if(isset($product->favoriteable->image[0]) && !empty($product->favoriteable->image[0]))
                                                    <img class="fit-images" src="{{ $product->favoriteable->image[0]->url }}" alt="favorit">
                                                @endif


                                                <button class="adtocart" title="حذف" data-toggle="modal" href="#delete{{ $product->favoriteable->id }}">
                                                    <i class="icon-trash "></i>
                                                </button>

                                            </div>

                                            <div class="card-body text-center">
                                                <h4>
                                                    <a target="_blank" href="{{ $product->favoriteable->path() }}" class="pro-title">
                                                        {{ $product->favoriteable->title }}                                            </a>
                                                </h4>
                                                <p class="price">
                                                    @php
                                                        $allPrice =   \App\Utility\sortPrice::sortPrice($product->favoriteable);
                                                         echo \App\Utility\sortPrice::totalPrice($allPrice);
                                                    @endphp
                                                </p>
                                            </div>
                                        </section>
                                    </div>
                                @endforeach


                                <span style="margin-right: 45%">{!! $favorites->render()  !!}</span>

                                <!-- Modal show -->
                                @foreach($favorites as $val)
                                    <div class="modal fade" id="delete{{ $val->favoriteable->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                    <h4 class="modal-title"><span class=""> حذف محصول  از لیست علاقه مندی </span></h4>
                                                </div>
                                                <div class="modal-body">
                                                    برای حذف  {{ $val->favoriteable->title  }} مطمئن هستید ؟
                                                </div>

                                                <form action="{{ route('panel.favorite.delete',$val->id) }}" method="POST">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <input type="hidden" name="product" value="{{ $val->favoriteable_id }}">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                   <div class="col-md-12">
                                                       <input type="submit" name="btndelete" value="@lang('cms.delete')" class="btn btn-danger pull-left">
                                                       <button type="button" class="btn btn-default pull-right" data-dismiss="modal">
                                                           @lang('cms.cancel')
                                                       </button>
                                                   </div>
                                                    <br>
                                                    <br>
                                                </form>
                                                <br>

                                            </div>
                                        </div>
                                        <!-- /.modal-content -->
                                    </div>
                            @endforeach
                            <!-- End of modal-dialog -->

                            </div>
                            <br>

                        @else
                            <br>
                            <p class="alert alert-info col-md-12 text-center border-right-info">لیست علاقه مندی شما خالی میباشد</p>
                            <br>
                            <br>
                        @endif
                    </div>
                </div>




            </section>
        </div>
    </div>

@endsection
