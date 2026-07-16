@if($countSize > 0)
<p>انتخاب سایز :‌ </p>
<select name="size" id="sizeAjax" class="form-control select-option">
   {{-- <option value="0"> انتخاب کنید</option>--}}
    <?php
    $sortVariation = $product->variations;
    $sortVariation = collect($sortVariation)->sortBy('price');
    ?>
    @foreach ($sortVariation as $itemVariation)
        @if($itemVariation->attributeTypeValue->id == $color && $itemVariation->user_id == $user_id->id && $itemVariation->count > 0 )
            @foreach ($itemVariation->relatedvariations as  $itemRelationVariation)
                <option value="{{$itemRelationVariation->attributeTypeValue->id}}" {{isset($sizeLowerPrice) && !empty($sizeLowerPrice['sizeLower']) &&  $itemRelationVariation->attributeTypeValue->id == $sizeLowerPrice['sizeLower'] ? "selected" : null }} >{{$itemRelationVariation->attributeTypeValue->value}}</option>
            @endforeach
        @endif
    @endforeach
</select>
@endif
