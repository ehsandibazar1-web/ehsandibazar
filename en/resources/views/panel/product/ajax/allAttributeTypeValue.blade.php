<div class="row">
    @if(isset($allAttributeTypeValue))
        @if(!empty($allAttributeTypeValue))
            <div class="col-md-4">
                <select name="attribute_type_id_related[]"
                        class="form-control select-option attribute_type_value_related_id js-example-basic-multiple">
                    <option value="">-- انتخاب کنید --</option>
                    @foreach($allAttributeTypeValue as $itemAttributeTypeValue)
                        @if($itemAttributeTypeValue->attribute_type_id != 3)
                            <option value="{{$itemAttributeTypeValue->id}}">{{$itemAttributeTypeValue->value}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        @else
            <div class="col-md-4" style="display: none">
                <select name="attribute_type_id_related[]"
                        class="form-control select-option attribute_type_value_related_id js-example-basic-multiple">
                    <option value=""></option>
                </select>
            </div>
        @endif
        @if(!empty($allAttributeTypeValue))
            <div class="col-md-4">
                @else
                    <div class="col-md-6">
                        @endif
                        <span class="">
        <input class="form-control input-height" min="0" placeholder="قیمت(تومان)" type="number" name="prices[]">
        <img src="{{url('admin_theme/img/fill.png')}}" style='margin-bottom: 5px;margin-top: 5px' alt="" width="16px">
        </span>
                    </div>


                    @if(!empty($allAttributeTypeValue))
                        <div class="col-md-4">
                            @else
                                <div class="col-md-6">
                                    @endif
                                    <span class="">
        <input class="form-control input-height" min="0" placeholder="موجودی" type="number" name="countes[]">
        <img src="{{url('admin_theme/img/fill.png')}}" style='margin-bottom: 5px;margin-top: 5px' width="16px" alt="">
        </span>
                                </div>
                                <br>
                                <br>
                                <br>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-8 col-xs-7">

        <textarea id="desc<?= isset($position) ? $position : null?>" placeholder="توضیحات اضافی"
                  style="width: 755px;margin-right: -25px" class="form-control t desc"
                  name="desc[]" cols="30" rows="10"></textarea>
                                    </div>
                                </div>
                            @endif
                        </div>




