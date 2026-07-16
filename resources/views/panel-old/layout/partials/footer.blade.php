<!-- js placed at the end of the document so the pages load faster -->
<script src="{{ Url('admin_theme/js/jquery.js') }}"></script>
<script src="{{ Url('admin_theme/js/jquery-1.8.3.min.js') }}"></script>
<script src="{{ Url('admin_theme/js/bootstrap.min.js') }}"></script>
<script src="{{ Url('admin_theme/js/jquery.scrollTo.min.js') }}"></script>
<script src="{{ Url('admin_theme/js/jquery.nicescroll.js') }}" type="text/javascript"></script>
<script src="{{ Url('admin_theme/js/jquery.sparkline.js') }}" type="text/javascript"></script>
<script src="{{ Url('admin_theme/assets/jquery-easy-pie-chart/jquery.easy-pie-chart.js') }}"></script>
<script src="{{ Url('admin_theme/js/owl.carousel.js') }}"></script>
<script src="{{ Url('admin_theme/js/jquery.customSelect.min.js') }}"></script>

<!--common script for all pages-->
<script src="{{ Url('admin_theme/js/common-scripts.js') }}"></script>


<!--script for this page-->
<script src="{{ Url('admin_theme/js/sparkline-chart.js') }}"></script>
<script src="{{ Url('admin_theme/js/easy-pie-chart.js') }}"></script>


@jquery
@toastr_js
@toastr_render
<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });
</script>

@include('panel-old.layout.ckjs')

@yield('admin-js')
{{--<script src="https://code.jquery.com/jquery-3.5.1.js"></script>--}}
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function () {
                $('#datatable').DataTable({
            "language": {
                "oPaginate": {sFirst: "اولین", sLast: "آخرین", sNext: "بعدی", sPrevious: "قبلی"},
                "lengthMenu": "نمایش _MENU_ صفحه در هر صفحه",
                "zeroRecords": "چیزی یافت نشد",
                "info": "نمایش صفحه _PAGE_ از _PAGES_",
                "infoEmpty": "رکوردی وجود ندارد",
                "sSearch": "",
                "sSearchPlaceholder": "جستجو کنید",
                "infoFiltered": "(فیلتر شده از _MAX_ رکورد)"
            },
            "columnDefs": [{
                "targets": 'no-sort',
                "orderable": false,
            }],"order": [[ 4, "desc" ]]
        });

    });
</script>
</body>
</html>
