<!DOCTYPE html>
<html class="" dir="rtl">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
    {!! Twitter::generate() !!}
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#d9bb75">



    <link rel="manifest" href="/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    <link rel="stylesheet" href="{{ asset('site_theme/css/main.min.css') }}"/>
    <link rel="stylesheet" href="{{ asset('site_theme/css/template.css') }}"/>
    @yield('site.css')
    <script src="{{ asset('site_theme/js/main-min.js') }}"></script>
@yield('site-js-header')
<!-- Google Tag Manager -->
    <script>(function (w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start':
                    new Date().getTime(), event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-P46MRK8');</script>
    <!-- End Google Tag Manager -->
</head>

<body class=" t-index has-top-banner">
<div class="page container-fluid p-0">
@include('site.layout.partials.header')
@yield('content')
@include('site.layout.partials.footer')
<!--
    <div class="box-search">
        <div class="search-inner">
            <form action="{{ route('site.search') }}" method="get" class="search-form">
                <div class="frm">
                    <input type="text" placeholder="جستجو کنید..." class="input-search" name="title">
                    <button class="btn-serch icon-theme"><i class="fal fa-search"></i></button>
                </div>
            </form>
            <div class="btSearchInnerClose icon-theme">
                <i class="fal fa-times-circle"></i>
            </div>
        </div>
    </div>
    -->

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <video controls width="100%">
                        <source src="" type="video/mp4">
                    </video>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="{{ asset('site_theme/js/script.js') }}"></script>
{{--Delete Basket Item--}}
<script>
    $('.cart-remove-span').on('click', function (e) {
        e.preventDefault();
        /* id product in session */
        var variationID = $(this).attr('attr-id');

        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            type: "post",
            url: "{{route('site.deleteFromBasket')}}",
            data: {
                variation_id: variationID,
                _token: CSRF_TOKEN
            },

            success: function (data) {
                console.log(data);

                if (data.status == 100) {
                    Swal.fire({
                        title: "error!",
                        text: "No products were found with this feature.",
                        icon: "error",
                        button: "Confirmation",
                    });
                }

                if (data.status == 200) {

                    Swal.fire({
                        title: "successful!",
                        text: "The product has been removed from your cart.",
                        icon: "success",
                        showCancelButton: false,
                        closeOnConfirm: false,
                        showLoaderOnConfirm: false,
                        confirmButtonClass: "btn-danger",
                        confirmButtonText: "close",
                    }).then(function () {
                        location.reload();
                    });
                }

                // location.reload();

                // $(".cart").load(" .cart > *");

            },
            error: function (error) {
                //alert(error);
                alert("Please log in in a few moments.");
            }
        });
    });
</script>
{{--Delete Basket Item--}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
@include('sweetalert::alert')
@yield('site-js')

<script>
    function compare(id, reload = 100) {
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        if (id != "") {
            $.ajax({
                type: "post",
                url: "{{ route('site.add.compare') }}",
                data: {
                    id: id,
                    reload: reload,
                    _token: CSRF_TOKEN
                },
                success: function (data) {
                    if (data.status == 200) {
                        $('.count-compare').text(data.count);
                        if (data.reload == 100) {
                            Swal.fire({
                                title: "@lang('cms.success')",
                                text: data.message,
                                icon: "success",
                                button: "@lang('cms.accept-2')",
                            });
                        } else {
                            Swal.fire({
                                title: "@lang('cms.success')",
                                text: data.message,
                                icon: "success",
                                button: "@lang('cms.accept-2')",
                            }).then(function () {
                                location.reload();
                            });
                        }
                    }
                    if (data.status == 150) {
                        Swal.fire({
                            title: "Dear user",
                            text: data.message,
                            icon: "warning",
                            button: "@lang('cms.accept-2')",
                        });
                    }


                },
                error: function (error) {

                    Swal.fire({
                        title: "@lang('cms.error')",
                        text: "@lang('cms.try-again-few-moments')",
                        icon: "error",
                        button: "@lang('cms.accept-2')",
                    });
                }
            });

        }
    }

    function removeCompare(id) {
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        if (id != "") {
            $.ajax({
                type: "post",
                url: "{{ route('site.remove.compare') }}",
                data: {
                    id: id,
                    _token: CSRF_TOKEN
                },
                success: function (data) {
                    if (data.status == 404) {
                        Swal.fire({
                            title: "@lang('cms.alert')",
                            text: data.message,
                            icon: "error",
                            button: "@lang('cms.accept-2')",
                        });
                    }
                    if (data.status == 200) {
                        Swal.fire({
                            title: "@lang('cms.success')",
                            text: data.message,
                            icon: "success",
                            button: "@lang('cms.accept-2')",
                        }).then(function () {
                            location.reload();
                        });
                    }
                },
                error: function (error) {
                    Swal.fire({
                        title: "@lang('cms.error')",
                        text: "@lang('cms.try-again-few-moments')",
                        icon: "error",
                        button: "@lang('cms.accept-2')",
                    });
                }
            });

        }
    }

    function favorite(id) {
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        if (id != "") {
            $.ajax({
                type: "post",
                url: "{{ route('add.favorites') }}",
                data: {
                    id: id,
                    _token: CSRF_TOKEN
                },
                success: function (data) {
                    if (data.status == 100) {
                        Swal.fire({
                            title: "@lang('cms.alert')",
                            text: data.msg,
                            icon: "error",
                            button: "@lang('cms.accept-2')",
                        });
                    }
                    if (data.status == 200) {
                        Swal.fire({
                            title: "@lang('cms.success')",
                            text: data.msg,
                            icon: "success",
                            button: "@lang('cms.accept-2')",
                        });
                    }
                    if (data.status == 101) {
                        Swal.fire({
                            title: "@lang('cms.alert')",
                            text: data.msg,
                            icon: "warning",
                            button: "@lang('cms.accept-2')",
                        });
                    }


                },
                error: function (error) {
                    Swal.fire({
                        title: "@lang('cms.error')",
                        text: "@lang('cms.try-again-few-moments')",
                        icon: "error",
                        button: "@lang('cms.accept-2')",
                    });
                }
            });

        }
    }
</script>
</body>

</html>