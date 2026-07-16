<?php
$collect = collect($product->categories[0]->attributes);
$attributeGroup = $collect->groupBy('attribute_group_id');
$arrayItemAttributeValue = [];
$i = 0;
?>

<div class="row">
    @foreach($attributeGroup as $idAttributeGroup => $valueAttributeGroup)

        @if(in_array($idAttributeGroup , $productAttributeGroup))
            <div class="col-md-12">
                <h2 class="h2-attributeGroup"> {{ \App\Model\AttributeGroup::whereId($idAttributeGroup)->first()->name}} </h2>
            </div>
            <?php $i++; ?>
        @endif

        @foreach($valueAttributeGroup as $attribute)
            @if(in_array($attribute->id , $productAttribute))

                <div class="col-md-5 background-key"> {{$attribute->name}} :</div>
                <div class="col-md-6 background-value">
                    @foreach($product->attributevalues as  $itemAttributeValue)

                        @if(in_array($itemAttributeValue->attribute_id,$arrayItemAttributeValue))
                            @if($attribute->id == $itemAttributeValue->attribute_id )
                                {!! $itemAttributeValue->value . "</br>"  !!}
                            @endif
                        @else
                            @if($attribute->id == $itemAttributeValue->attribute_id )
                                {{$itemAttributeValue->value}}
                            @endif
                        @endif

                       @php  $arrayItemAttributeValue[] = $itemAttributeValue->attribute_id @endphp
                    @endforeach
                </div>

            @endif

        @endforeach

    @endforeach

    @if($i <= 0)
        <p class="text-center alert alert-info border-right-info w-100"> مشخصاتی وارد نشده
            است. </p>
    @endif

</div>


