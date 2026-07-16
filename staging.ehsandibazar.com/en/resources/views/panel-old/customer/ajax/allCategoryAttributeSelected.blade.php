@if(isset($findCategory) && !empty($findCategory) &&  $findCategory->count() > 0)

    @foreach ($arrayAttributesGroup as $itemGroup)
        <h2 class="attributeGroup">{{ $itemGroup->name }}</h2>
        <div class="attributes-border">
            @foreach($arrayAttributes as $itemAttribute)
                @if($itemAttribute->attribute_group_id ==  $itemGroup->id)


                    <div class="form-group">
                        <label for="attributes" class="control-label col-lg-2 ">
                            {{$itemAttribute->name}}
                        </label>
                        <div class="col-lg-10">
                            <input type="text" name="attributes[{{$itemAttribute->id}}][]"
                                   value="
@if(isset($findProductAttribute) && !empty($findProductAttribute))
@foreach($findProductAttribute as $itemAttributeProductValue)
@if($itemAttributeProductValue->attribute_id ==  $itemAttribute->id)
<?= $itemAttributeProductValue->value ?>
@endif
@endforeach
@endif" class="form-control"/>

                            <br>
                            <br>
                            <div class="col-lg-12" id="{{$itemAttribute->id}}" data-result-attr="{{$itemAttribute->id}}">
                            </div>

                        </div>





                        <div class="col-lg-10 buttons-ex">
                            <span data-attrs-filit="{{$itemAttribute->id}}"
                                  class="btn btn-xs btn-success exAttributes">+</span>
                            <span data-attrs-filit="{{$itemAttribute->id}}"
                                  class="btn btn-xs btn-danger delAttributes">-</span>
                        </div>
                    </div>

                @endif
            @endforeach
        </div>

    @endforeach

@endif
