
<option value="0" selected> دسته اصلی</option>
@foreach($categories as $item)
    <option
        value="{{ $item->id }}" {{ isset($find) && $find->id == $item->id ? 'selected' : null }}>{{ $item->title }}</option>
@endforeach
<script src="{{ url('admin/assets/js/pages/forms/advanced-form-elements.js')  }}"></script>
