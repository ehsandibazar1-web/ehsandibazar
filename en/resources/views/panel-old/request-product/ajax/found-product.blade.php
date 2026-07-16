@if(isset($findProductRequest) && !empty($findProductRequest))
<div id="imgFound" class="col-md-12">

    <div class="text-right col-xs-8 col-md-9">

        <div><span class="dark-blue">نام محصول :</span>‌  <span> {{$findProductRequest->title}} </span></div>
        <br>
        <div><span class="dark-blue">توضیحات :</span>‌ <span>{!! str_limit($findProductRequest->description , 70) !!}</span></div>
        <a target="_blank"  href="{{route('site.products',['slug' => $findProductRequest->slug])}}"><div><span class="dark-blue">نمایش محصول:</span> <span class="btn btn-xs btn-info position-button-details"> کلیک کنید </span></div></a>

    </div>

    <div class="text-left col-xs-4 col-md-3">
        <img  class="img-rounded" width="100px" height="100px" src="{{url($findProductRequest->image[0]->url)}}" alt="sampleProduct">
    </div>


</div>
<br><br>
@endif    
