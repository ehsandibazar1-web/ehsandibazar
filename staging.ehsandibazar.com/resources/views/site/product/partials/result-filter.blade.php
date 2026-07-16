<div class="row">
    @if(isset($products))
        @foreach($products as $product)
            <div class="col-12 col-md-4">
                @if($loop->last)
                    <input type="hidden" name="lastID" id="lastID" value="{{ $product->id }}">
                @endif
                <div class="result-item">
                    <div class="result-offered-image">
                        <a href="{{ $product->path() }}">
                            <img src="{{ isset($product->image[0]) ? $product->image[0]->url : null }}">
                        </a>
                    </div>
                    <div class="result-offered-title">
                        <a href="{{ $product->path() }}">
                            <h5>{{ $product->title }}</h5>
                        </a>
                    </div>
                    <div class="result-offered-price">
                        <ul>
                            <li>
                                @php
                                    $allPrice =   \App\Utility\sortPrice::sortPrice($product);
                                    echo \App\Utility\sortPrice::totalPrice($allPrice);
                                @endphp
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>
<input type="hidden" class="countProduct" value="{{ isset($countProduct) ? $countProduct : 0 }}">
