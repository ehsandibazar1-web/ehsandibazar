<!DOCTYPE html>
<html lang="fa-IR" dir="rtl">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {!! SEOMeta::generate() !!}
    {!! OpenGraph::generate() !!}
    {!! Twitter::generate() !!}
    <link rel="preload" href="{{ asset('site_themes/fonts/Vazir-Medium.woff2') }}" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="{{ asset('site_themes/fonts/Vazir.woff2') }}" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="{{ asset('site_themes/webfonts/fa-solid-sub.woff') }}" as="font" type="font/woff" crossorigin>
<link rel="preload" href="{{ asset('site_themes/webfonts/fa-light-sub.woff') }}" as="font" type="font/woff" crossorigin>
<link rel="preload" href="{{ asset('site_themes/webfonts/fa-brands-sub.woff') }}" as="font" type="font/woff" crossorigin>
    <meta charset="utf-8">
    
<meta name="robots" content="index,follow">
    @if(request()->url()=='https://ehsandibazar.com/category/Self-Defense')
         <link rel="canonical" href="https://ehsandibazar.com/page/%D8%A2%D9%85%D9%88%D8%B2%D8%B4-%D8%AF%D9%81%D8%A7%D8%B9-%D8%B4%D8%AE%D8%B5%DB%8C">
    @else
   
       <link rel="canonical" href="{{request()->url()}}">
    @endif
    
    
    
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#d9bb75">
    <link rel="manifest" href="/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <link rel="preconnect" href="https://www.googletagmanager.com" crossorigin>
<link rel="preconnect" href="https://www.google-analytics.com" crossorigin>
<link rel="preconnect" href="https://www.clarity.ms" crossorigin>

<link rel="stylesheet" href="{{ asset('site_themes/css/site.min.css') }}"/>
<style>/* FontAwesome Subset - فقط آیکون‌های استفاده‌شده */

.fa,.fab,.fal,.far,.fas{-moz-osx-font-smoothing:grayscale;-webkit-font-smoothing:antialiased;display:inline-block;font-style:normal;font-variant:normal;text-rendering:auto;line-height:1}

@font-face{font-family:"Font Awesome 5 Pro";font-style:normal;font-weight:900;font-display:swap;src:url('/site_themes/webfonts/fa-solid-sub.woff') format('woff')}
@font-face{font-family:"Font Awesome 5 Pro";font-style:normal;font-weight:300;font-display:swap;src:url('/site_themes/webfonts/fa-light-sub.woff') format('woff')}
@font-face{font-family:"Font Awesome 5 Brands";font-style:normal;font-weight:400;font-display:swap;src:url('/site_themes/webfonts/fa-brands-sub.woff') format('woff')}

.fa,.fas{font-family:"Font Awesome 5 Pro";font-weight:900}
.fal{font-family:"Font Awesome 5 Pro";font-weight:300}
.fab{font-family:"Font Awesome 5 Brands";font-weight:400}

/* fas - Solid */
.fa-calendar-alt:before{content:"\f073"}
.fa-check:before{content:"\f00c"}
.fa-clock:before{content:"\f017"}
.fa-comment:before{content:"\f075"}
.fa-envelope:before{content:"\f0e0"}
.fa-eye:before{content:"\f06e"}
.fa-link:before{content:"\f0c1"}
.fa-list-ul:before{content:"\f0ca"}
.fa-play-circle:before{content:"\f144"}
.fa-question-circle:before{content:"\f059"}
.fa-share-alt:before{content:"\f1e0"}
.fa-user:before{content:"\f007"}
.fa-user-circle:before{content:"\f2bd"}

/* fal - Light */
.fa-bars:before{content:"\f0c9"}
.fa-chevron-down:before{content:"\f078"}
.fa-chevron-up:before{content:"\f077"}
.fa-long-arrow-left:before{content:"\f177"}
.fa-search:before{content:"\f002"}
.fa-shopping-cart:before{content:"\f07a"}
.fa-times-circle:before{content:"\f057"}
.fa-user:before{content:"\f007"}

/* fab - Brands */
.fa-instagram:before{content:"\f16d"}
.fa-telegram:before{content:"\f2c6"}
.fa-telegram-plane:before{content:"\f3fe"}
.fa-whatsapp:before{content:"\f232"}
.fa-youtube:before{content:"\f167"}</style>
    <style>
        @font-face{font-family:Vazir-Medium;...;font-display:block}
@font-face{font-family:Vazir-Medium;...;font-display:block}
    </style>
     

    @yield('site.css')
    @yield('site-js-header')
    @yield('site-json-ld')

    <!--
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-229939247-2"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-229939247-2');
</script>
-->

    <!-- Global site tag (gtag.js) - Google Ads: AW-18235155408 -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=AW-18235155408"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    
    gtag('config', 'AW-18235155408');
    </script>
    
    
    
    
    
    
  
 <!--   
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-FMJ1DZ4F80"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
    
      gtag('config', 'G-FMJ1DZ4F80');
    </script>
 --> 
  

    <!-- Microsoft Clarity -->
<script type="text/javascript">
    (function(c,l,a,r,i,t,y){
        c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
        t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
        y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
    })(window, document, "clarity", "script", "x7v6ymnw5g");
</script>

<!-- jQuery Lazyload plugin -->

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
                    
                    <span class="modal-title" id="exampleModalLabel"></span>
                    
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
    
   


<script src="{{ asset('site_themes/js/main-min.js') }}"></script>
<script src="{{ asset('site_themes/js/script.js') }}"></script>
<script>
    // Video Modal Fix: modal ساده بدون پلاگین (رفع باگ چند modal همزمان)
    (function() {
        if (!window._videoQueue || !window._videoQueue.length) return;

        var videoMap = {};
        window._videoQueue.forEach(function(v) {
            videoMap[v.selector.replace('.', '')] = v.url;
        });

        function openVideoModal(url) {
            var overlay = document.createElement('div');
            overlay.style.cssText = 'position:fixed;inset:0;background:rgba(0,0,0,0.85);z-index:99999;display:flex;align-items:center;justify-content:center;padding:20px;';
            var box = document.createElement('div');
            box.style.cssText = 'position:relative;width:100%;max-width:840px;';
            var closeBtn = document.createElement('button');
            closeBtn.innerHTML = '&times;';
            closeBtn.setAttribute('aria-label', 'بستن');
            closeBtn.style.cssText = 'position:absolute;top:-42px;left:0;background:none;border:none;color:#fff;font-size:36px;cursor:pointer;line-height:1;';
            var frameWrap = document.createElement('div');
            frameWrap.style.cssText = 'position:relative;padding-bottom:56.25%;height:0;';
            var iframe = document.createElement('iframe');
            iframe.src = url;
            iframe.setAttribute('allowfullscreen', 'true');
            iframe.setAttribute('allow', 'autoplay');
            iframe.style.cssText = 'position:absolute;inset:0;width:100%;height:100%;border:0;';
            frameWrap.appendChild(iframe);
            box.appendChild(closeBtn);
            box.appendChild(frameWrap);
            overlay.appendChild(box);
            document.body.appendChild(overlay);
            document.body.style.overflow = 'hidden';

            function close() {
                document.body.removeChild(overlay);
                document.body.style.overflow = '';
            }
            closeBtn.addEventListener('click', close);
            overlay.addEventListener('click', function(e) {
                if (e.target === overlay) close();
            });
        }

        document.addEventListener('click', function(e) {
            var btn = e.target.closest('.js-video-button');
            if (!btn) return;
            var url = null;
            btn.classList.forEach(function(cls) {
                if (videoMap[cls]) url = videoMap[cls];
            });
            if (!url) return;
            e.preventDefault();
            openVideoModal(url);
        });
    })();
</script>
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
                        title: "خطا!",
                        text: "محصولی با این مشخصه یافت نشد.",
                        icon: "error",
                        button: "تایید",
                    });
                }

                if (data.status == 200) {

                    Swal.fire({
                        title: "موفقیت آمیز!",
                        text: "محصول مورد نظر از سبد خرید شما حذف شد.",
                        icon: "success",
                        showCancelButton: false,
                        closeOnConfirm: false,
                        showLoaderOnConfirm: false,
                        confirmButtonClass: "btn-danger",
                        confirmButtonText: "بستن",
                    }).then(function () {
                        location.reload();
                    });
                }

                // location.reload();

                // $(".cart").load(" .cart > *");

            },
            error: function (error) {
                //alert(error);
                alert("لطفا چند لحظه دیگر وارد شوید.");
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
                            title: "کاربر گرامی",
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
  @if(request()->url()=='https://ehsandibazar.com/page/%D8%A2%D9%85%D9%88%D8%B2%D8%B4-%D8%AF%D9%81%D8%A7%D8%B9-%D8%B4%D8%AE%D8%B5%DB%8C')
 
 <script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
        {
            "@type": "ListItem",
            "position": 1,
            "name": "صفحه اصلی",
            "item": "https://ehsandibazar.com/"
        },
        {
            "@type": "ListItem",
            "position": 2,
            "name": "آموزش دفاع شخصی",
            "item": "https://ehsandibazar.com/page/%D8%A2%D9%85%D9%88%D8%B2%D8%B4-%D8%AF%D9%81%D8%A7%D8%B9-%D8%B4%D8%AE%D8%B5%DB%8C"
        }
    ]
}
</script>
 
 
  <script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [{
    "@type": "Question",
    "name": "دوره های آموزش دفاع شخصی به چه صورتی برگزار میگردد؟",
    "acceptedAnswer": {
      "@type": "Answer",
      "text": "دوره های آموزش دفاع شخصی به دو صورت آموزش خصوصی و دوره های از پیش ضبط شده برگزار میگردد."
    }
  },{
    "@type": "Question",
    "name": "آموزش دفاع شخصی خصوصی مناسب چه کسانی است؟",
    "acceptedAnswer": {
      "@type": "Answer",
      "text": "این آموزش مخصوص افرادی است که قصد دارند دفاع شخصی را به صورت حرفه ای و تخصصی یاد بگیرند."
    }
  },{
    "@type": "Question",
    "name": "آموزش ویدیویی آیا کابردی است؟",
    "acceptedAnswer": {
      "@type": "Answer",
      "text": "بله آموزش ویدیویی از نظر کیفیت دارای کیفیت بالایی است و مدرس دوره استاد احسان دیبازر تمامی مفاهیم دفاع شخصی و فنون را به صورت کامل توضیح میدهد و سپس اجرا مینماید همچنین در صورتی که قصد دارید از کیفیت دوره اطمینان حاصل نمایید میتوانید دوره دمو را مشاهده کنید."
    }
  }]
}
</script>
  @endif
</body>

</html>