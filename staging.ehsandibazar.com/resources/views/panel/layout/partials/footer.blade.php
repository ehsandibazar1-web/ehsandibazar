
<script src=" {{ url('admin/assets/js/app.min.js')  }} "></script>
<script src=" {{ url('admin/assets/js/chart.min.js')  }} "></script>
<!-- Custom Js -->
<script src=" {{ url('admin/assets/js/admin.js')  }} "></script>
<script src=" {{ url('admin/assets/js/pages/dashboard/dashboard3.js')  }} "></script>
<!-- Knob Js -->
<script src=" {{ url('admin/assets/js/pages/todo/todo.js')  }} "></script>

{{--@if(request()->route()->getName() != "panel.order.index")--}}
<script src="{{ url('admin/assets/js/form.min.js')  }}"></script>
<script src="{{ url('admin/assets/js/pages/forms/advanced-form-elements.js')  }}"></script>
{{--@endif--}}

<script src="{{ url('admin/assets/js/bundles/multiselect/js/jquery.multi-select.js')  }}"></script>
<script src="{{ url('admin/assets/js/bundles/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.js')  }}"></script>

<script src="{{ url('admin/assets/js/table.min.js')  }}"></script>
<script src="{{ url('admin/assets/js/pages/tables/jquery-datatable.js')  }}"></script>

@include('panel.layout.ckjs')
@include('sweetalert::alert')
<script src="https://unpkg.com/persian-date@1.1.0/dist/persian-date.min.js" type="text/javascript"></script>
<script src="https://unpkg.com/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"
        type="text/javascript"></script>
@yield('admin-js')
</body>

</html>
