<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="Mosaddek">
    <meta name="keyword" content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
    <link rel="shortcut icon" href="theme/img/favicon.html">
    @toastr_css

    {{--<title>@yield('title')</title>--}}
    <title> {{isset($title) ? \Illuminate\Support\Facades\Lang::get('cms.dashboard').$title : \Illuminate\Support\Facades\Lang::get('cms.dashboard-panel') }} </title>

    <!-- Bootstrap core CSS -->
    <link href="{{ Url('admin_theme/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ Url('admin_theme/css/bootstrap-reset.css') }}" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css" rel="stylesheet">

    <!--external css-->
    <link href="{{ Url('admin_theme/assets/font-awesome/css/font-awesome.css') }}" rel="stylesheet"/>
    <link href="{{ Url('admin_theme/assets/jquery-easy-pie-chart/jquery.easy-pie-chart.css') }}" rel="stylesheet"
          type="text/css" media="screen"/>
    <link rel="{{ Url('stylesheet" href="theme/css/owl.carousel.css') }}" type="text/css">
    <!-- Custom styles for this template -->
    <link href="{{ Url('admin_theme/css/style.css') }}" rel="stylesheet">
    <link href="{{ Url('admin_theme/css/style-responsive.css') }}" rel="stylesheet"/>
    <link rel="stylesheet" href="{{url('admin_theme/css/custom-admin.css')}}">

    @yield('admin-css')


</head>

<body>
