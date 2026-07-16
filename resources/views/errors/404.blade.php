@extends('site.layout.master')
@section('title')
    404
@endsection
@section('header')
   {{-- @include('site.layout.product.page-header')--}}
@endsection

@section('content')
    <div class="not_found_page">
        <!--/*****************************content*************************/-->
        <div class="container">
            <div class="row">
                <div class="col-md-5 col-sm-5 col-xs-12">
                    <span class="error-num-404">404</span>
                </div>
                <div class="col-md-7 col-sm-7 col-xs-12">
                    <div id="page-404" class="wrap">
                        <div id="primary" class="content-area">
                            <main id="main" class="site-main" role="main">
                                <section class="error-404 not-found">
                                    <h1 class="not-found-page-title">متاسفانه صفحه موردنظر شما یافت نشد </h1>
                                    <div class="page-content">
                                        <div  id="search_form" class="search_form">
                                            <form role="search" method="get" id="searchform" class="searchform" action="">
                                                <input value="" name="s" id="s" type="text" placeholder="جستجو در وبسایت">
                                                <input class="btn-ctm" id="searchsubmit" value="جستجو" type="submit">
                                            </form>
                                        </div>
                                    </div><!-- .page-content -->
                                </section><!-- .error-404 -->
                            </main><!-- #main -->
                        </div><!-- #primary -->
                    </div><!-- .wrap -->
                </div>
            </div>
        </div>
    </div>

@endsection
