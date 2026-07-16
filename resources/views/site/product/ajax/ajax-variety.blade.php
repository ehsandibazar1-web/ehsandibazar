<div class="container-fluid">
    {{-- when color count > 0 --}}
    @if(isset($isColor) && !empty($isColor) && $isColor > 0)
        @if(isset($product) && isset($product->variations))
            <p>انتخاب رنگ :‌ </p>

            <?php
            $sortVariation = $product->variations;
            $sortVariation = collect($sortVariation)->sortBy('price');
            ?>
            <?php $arrayVariations = []  ?>
            <select name="color" id="color" class="form-control select-option">
                {{--  <option value="0"> انتخاب کنید</option>--}}
                @foreach($sortVariation as $itemVariation)
                    {{-- just color --}}
                    @if($itemVariation->attributeTypeValue->attribute_type_id == \App\Utility\Variation::COLOR && $itemVariation->user_id == $user_id->id && $itemVariation->count > 0)
                        @if($itemVariation->user_id == $user_id->id && !in_array($itemVariation->attributeTypeValue->id , $arrayVariations) )
                            <option
                                value="{{$itemVariation->attributeTypeValue->id}}">{{ $itemVariation->attributeTypeValue->value  }}

                            </option> <span style="width: 100px;height: 100px;background-color: #95a031"></span>
                        @endif
                        <?php $arrayVariations [] = $itemVariation->attributeTypeValue->id;?>
                    @endif
                @endforeach
            </select>

            <br>

            <div id="resultColor"></div>
        @endif
        {{-- when color count  <= 0 , size showying  --}}
    @elseif(isset($isSize) && !empty($isSize) && $isSize > 0 )

        @if(isset($product) && isset($product->variations))
            <p>انتخاب سایز :‌ </p>
            <?php
            $sortVariation = $product->variations;
            $sortVariation = collect($sortVariation)->sortBy('price');
            ?>
            <?php $arrayVariations = []  ?>
            <select name="size" id="size" class="form-control select-option">
                {{--   <option value="0"> انتخاب کنید</option>--}}
                @foreach($sortVariation as $itemVariation)
                    {{-- just size --}}
                    @if($itemVariation->attributeTypeValue->attribute_type_id == \App\Utility\Variation::SIZE && $itemVariation->user_id == $user_id->id && $itemVariation->count > 0)
                        @if( $itemVariation->user_id == $user_id->id && !in_array($itemVariation->attributeTypeValue->id , $arrayVariations) )
                            <option
                                value="{{$itemVariation->attributeTypeValue->id}}">{{ $itemVariation->attributeTypeValue->value  }}
                            </option>
                        @endif
                        <?php $arrayVariations [] = $itemVariation->attributeTypeValue->id;?>
                    @endif
                @endforeach
            </select>
        @endif
    @endif

</div>
