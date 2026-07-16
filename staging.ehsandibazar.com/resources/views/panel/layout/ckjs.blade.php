@if(\Illuminate\Support\Facades\Route::getCurrentRoute()->getName() != "panel.profile.index")
    <script src="{{ Url('admin/assets/ckeditor/ckeditor.js') }}" type="text/javascript"></script>
    <script src="{{ Url('admin/assets/ckeditor/config.js') }}" type="text/javascript"></script>
    <!--<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>-->
<!--    <script src="{{ Url('admin/assets/ckeditor/ckeditor.js') }}" type="text/javascript"></script>-->
<!--<script src="{{ Url('admin/assets/ckeditor/config.js') }}" type="text/javascript"></script>-->
<!--<script src="//cdnjs.cloudflare.com/ajax/libs/ckeditor/4.5.11/ckeditor.js"></script>-->
<script src="//cdnjs.cloudflare.com/ajax/libs/ckeditor/4.5.11/adapters/jquery.js"></script>
<script src="https://ehsandibazar.com/vendor/laravel-filemanager/js/stand-alone-button.js"></script>

    <script>
        var route_prefix = "{{ url(config('lfm.url_prefix', config('lfm.prefix'))) }}";
        var options = {
    height: 250,
    filebrowserImageBrowseUrl: route_prefix + '?type=Images',
    filebrowserImageUploadUrl: route_prefix + '/upload?type=Images&_token={{csrf_token()}}',
    filebrowserBrowseUrl: route_prefix + '?type=Files',
    filebrowserUploadUrl: route_prefix + '/upload?type=Files&_token={{csrf_token()}}'
};

$('textarea.ckeditor').ckeditor(options);

        $('#lfm').filemanager('image', "", "{{env('APP_URL')}}");
        $('.lfm').filemanager('image', "", "{{env('APP_URL')}}");
        $('#lfm1').filemanager('image', "", "{{env('APP_URL')}}");
        $('.lfm1').filemanager('file', "", "{{env('APP_URL')}}");
    </script>
@endif
