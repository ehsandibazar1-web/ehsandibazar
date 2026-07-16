{{--@if(isset($tags) && count($tags) > 0)--}}
    {{-- tags --}}
    <div class="row clearfix">
        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
            <label for="status">برچسب ها
            </label>
        </div>
        @php
            $i = 0;
        @endphp
        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
            <div class="form-line">
                <input type="text" class="form-control" data-role="tagsinput"
                       name="tags"
                       value="@if(isset($find) && !empty($find)) @foreach($find->tags as $tag) @php $i++ @endphp {{ $tag->title }} {{ $i < count($find->tags)  ? ',' : '' }} @endforeach @endif">
            </div>


        </div>
    </div>
{{--@endif--}}
