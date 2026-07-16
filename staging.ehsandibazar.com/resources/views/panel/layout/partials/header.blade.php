<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    {!! SEO::generate() !!}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicon-->
    <link rel="icon" href="../../assets/images/favicon.ico" type="image/x-icon">
    <!-- Plugins Core Css -->
    <link href="{{ url('admin/assets/css/app.min.css')  }}" rel="stylesheet">
    <link href=" {{ url('admin/assets/js/bundles/materialize-rtl/materialize-rtl.min.css')  }} " rel="stylesheet">

{{--    @toast_css--}}

    <!-- Custom Css -->
    <link href=" {{ url('admin/assets/css/style.css')  }} " rel="stylesheet" />
    <!-- You can choose a theme from css/styles instead of get all themes -->
    <link href=" {{ url('admin/assets/css/styles/all-themes.css')  }} " rel="stylesheet" />
    <link href="{{ url('admin/assets/css/form.min.css')  }}" rel="stylesheet">
    <link href=" {{ url('admin/assets/css/hemmat.css')  }} " rel="stylesheet" />
    <link href=" {{ url('admin/assets/css/custom-admin.css')  }} " rel="stylesheet" />
    <link href="{{ url('admin/assets/css/sweetalert.css')  }}" rel="stylesheet">
    <link href="https://unpkg.com/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css" rel="stylesheet"/>


    @yield('admin-css')
</head>
<body class="light rtl">
