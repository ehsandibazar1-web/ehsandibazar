@if(isset($mode,$attribute) && !empty($mode))
    @foreach($attribute->attributeValues as $itemValue)
        <div>
            <div class="row clearfix" id="item">
                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                    <label for="attribute"></label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-8 col-xs-7">
                    <div class="form-group">
                        <div class="">
                            <input id="attribute" class="form-control" type="text"
                                   name="attributes[]" value="{{$itemValue->value}}"
                                   placeholder="مقدار ویژگی را وارد نمایید">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    @endforeach
@else
    <div>
        <div class="row clearfix" id="item">
            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                <label for="attribute"></label>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-8 col-xs-7">
                <div class="form-group">
                    <div class="">
                        <input id="attribute" class="form-control" type="text"
                               name="attributes[]"
                               placeholder="مقدار ویژگی را وارد نمایید">
                    </div>
                </div>
            </div>

        </div>
    </div>

@endif


