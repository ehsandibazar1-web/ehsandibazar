@extends('site.layout.master')

@section('site.css')

<style>
    img { max-width: 100%; height: auto; }

    #reading-progress { position:fixed !important; top:0 !important; left:0 !important; right:auto !important; width:0 !important; height:4px !important; background:linear-gradient(to right,#d9bb75,#f5e27a) !important; z-index:2147483647 !important; pointer-events:none !important; transition:width 0.15s linear; direction:ltr !important; }

    .article-meta { display:flex; flex-wrap:wrap; align-items:center; gap:14px; padding:10px 0; border-top:1px solid #eee; border-bottom:1px solid #eee; margin-bottom:1.2rem; font-size:13px; color:#666; direction:rtl; }
    .article-meta span { display:flex; align-items:center; gap:5px; }
    .article-meta i { color:#d9bb75; font-size:13px; }

    .toc-box { background:#fafaf8; border:1px solid #e8e3d5; border-right:4px solid #d9bb75; border-radius:8px; padding:18px 22px; margin:1.5rem 0; direction:rtl; }
    .toc-box h2 { font-size:14px; font-weight:700; margin-bottom:10px; color:#333; }
    .toc-box ul { list-style:none; padding:0; margin:0; counter-reset:toc-c; }
    .toc-box ul li { counter-increment:toc-c; padding:5px 0; font-size:13px; border-bottom:1px dashed #e5e5e5; color:#444; }
    .toc-box ul li:last-child { border-bottom:none; }
    .toc-box ul li::before { content:counter(toc-c) ". "; color:#d9bb75; font-weight:700; margin-left:5px; }
    .toc-box ul li a { color:#444; text-decoration:none; }

    .share-box { background:#f9f7f2; border:1px solid #e8e3d5; border-radius:10px; padding:16px 18px; margin:1.5rem 0; direction:rtl; }
    .share-box h3 { font-size:13px; color:#888; margin-bottom:10px; font-weight:600; }
    .share-buttons { display:flex; flex-wrap:wrap; gap:9px; }
    .share-btn { display:inline-flex; align-items:center; gap:6px; padding:7px 16px; border-radius:25px; font-size:13px; font-weight:600; text-decoration:none; border:none; cursor:pointer; transition:opacity 0.2s; }
    .share-btn-tg { background:#0088cc; color:#fff; }
    .share-btn-wa { background:#25D366; color:#fff; }
    .share-btn-cp { background:#f0f0f0; color:#333; }
    .share-btn-cp.copied { background:#d9bb75; color:#fff; }

    .hoosh-box { background:linear-gradient(135deg,#1a1a1a 0%,#2d2410 100%); border-radius:14px; padding:26px; margin:1.5rem 0; direction:rtl; }
    .hoosh-box p { font-size:14px; color:#bbb; line-height:2; margin-bottom:10px; }
    .hoosh-box p strong { color:#d9bb75; }
    .hoosh-btn { display:inline-flex; align-items:center; gap:7px; background:#d9bb75; color:#1a1a1a; font-size:14px; font-weight:700; padding:11px 22px; border-radius:8px; text-decoration:none; margin-top:18px; }

    /* Typography - match article */
    .site-blog-post__box__body__detail p { text-align:justify; line-height:2.2; margin-bottom:1.2rem; font-size:15px; color:#333; }
    .site-blog-post__box__body__detail h2 { font-size:1.3rem; font-weight:800; margin:2rem 0 1rem; color:#222; }
    .site-blog-post__box__body__detail h3 { font-size:1.1rem; font-weight:700; margin:1.5rem 0 0.8rem; color:#333; }
    .site-blog-post__box__body__detail ul, .site-blog-post__box__body__detail ol { padding-right:1.5rem; margin-bottom:1rem; line-height:2.2; }
    .site-blog-post__box__body__detail li { margin-bottom:0.4rem; font-size:15px; color:#333; }
    .site-blog-post__box__body__detail a { color:#8a6d1f; font-weight:700; }
    .site-blog-post__box__body__detail img { max-width:100%; border-radius:8px; margin:1rem 0; }
    .site-blog-post__box__body__detail blockquote { border-right:4px solid #d9bb75; padding:10px 20px; background:#fafaf8; margin:1.5rem 0; font-style:italic; color:#555; }

    /* Sidebar - match article style */
    .site-blog-post__last__item { padding:10px 0; border-bottom:1px solid #f0f0f0; }
    .site-blog-post__last__item__desc h5 a { color:#333 !important; text-decoration:none; font-size:13px; font-weight:600; }
    .site-blog-post__last__item__desc h5 a:hover { color:#d9bb75 !important; }
    .site-blog-post__last__item__desc .badge { font-size:11px; padding:3px 8px; border-radius:4px; display:inline-block; margin-top:4px; }
    .badge-danger { background:#d9bb75; color:#1a1a1a; }
    .badge-dark { background:#333; color:#fff; }

    /* Related articles - match article style */
    .site-blog__posts__item__desc__title a { color:#333 !important; text-decoration:none; }
    .site-blog__posts__item__desc__title a:hover { color:#d9bb75 !important; }
    .site-blog__posts__item__desc__list { list-style:none; padding:0; margin:5px 0; display:flex; gap:8px; flex-wrap:wrap; }
    .site-blog__posts__item__desc__list li { font-size:12px; color:#888; }
    .site-blog__posts__item__desc__detail { font-size:13px; color:#666; line-height:1.8; }
    .site-blog__sidebar__item__header fieldset { border:none; border-bottom:2px solid #d9bb75; padding:0; margin:0 0 15px 0; }
    .site-blog__sidebar__item__header fieldset legend { font-size:16px; font-weight:700; color:#333; padding:0 10px 0 0; }

    /* Badge دسته‌بندی روی تصویر */
    .site-blog__posts__item__image { position:relative; overflow:hidden; }
    .site-blog__posts__item__cat { position:absolute; bottom:0; right:0; }
    .site-blog__posts__item__cat__label { background:#d9bb75; color:#1a1a1a; font-size:11px; font-weight:700; padding:4px 10px; display:inline-block; }

    /* عنوان مطالب مرتبط */
    .site-blog__posts__item__desc__title { margin:8px 0 4px; }
    .site-blog__posts__item__desc__title a { color:#222 !important; text-decoration:none; font-size:14px; font-weight:700; line-height:1.6; }
    .site-blog__posts__item__desc__title a:hover { color:#d9bb75 !important; }
    .toc-box--hidden {
    opacity: 0 !important;
    pointer-events: none !important;
}
</style>
@endsection

@section('site-js-header')

@endsection

@section('content')

<div id="reading-progress"></div>

<main class="site-blog-post wrapper default">
    <div class="container">

        <nav class="site-blog-post__path">
            <ul>
                <li><a href="{{ route('site.index') }}">خانه</a></li>
                <li><a href="{{ $page->path() }}">{{ $page->title }}</a></li>
            </ul>
        </nav>

        <div class="site-blog-post__box">
            <div class="row">
                <div class="col-12 col-md-8">
                    <article class="site-blog-post__box__body">

                        @if(isset($page->image[0]))
                        <div class="site-blog-post__box__body__image">
                            <img src="{{ url($page->image[0]->url) }}" alt="{{ $page->title }}" fetchpriority="high" decoding="async">
                        </div>
                        @endif

                        <h1>{{ $page->title }}</h1>

                        <div class="article-meta">
                            <span><i class="fas fa-calendar-alt"></i> {{ $page->created_at }}</span>
                            <span><i class="fas fa-eye"></i> {{ number_format($page->viewCount ?? 0) }} بازدید</span>
                        </div>

                        @php
                            $tocData = buildTableOfContents(lazyLoadAparatIframes(fixImageDimensions($page->body)), ['h2']);
                        @endphp
                        <div id="toc-container" class="toc-box @if($tocData['count'] < 2) toc-box--hidden @endif">
                            <h2><i class="fas fa-list-ul"></i> فهرست مطالب</h2>
                            <ul id="toc-list">{!! $tocData['list'] !!}</ul>
                        </div>

                        <div id="article-content" class="site-blog-post__box__body__detail">
                            {!! $tocData['html'] !!}
                        </div>

                        <div class="share-box">
                            <h3><i class="fas fa-share-alt" style="color:#d9bb75;"></i> این صفحه رو به اشتراک بذار</h3>
                            <div class="share-buttons">
                                <a href="https://t.me/share/url?url={{ urlencode(url($page->path())) }}&text={{ urlencode($page->title) }}" target="_blank" rel="noopener" class="share-btn share-btn-tg"><i class="fab fa-telegram-plane"></i> تلگرام</a>
                                <a href="https://wa.me/?text={{ urlencode($page->title . ' ' . url($page->path())) }}" target="_blank" rel="noopener" class="share-btn share-btn-wa"><i class="fab fa-whatsapp"></i> واتساپ</a>
                                <button class="share-btn share-btn-cp" onclick="copyLink(this)" data-url="{{ url($page->path()) }}"><i class="fas fa-link"></i> کپی لینک</button>
                            </div>
                        </div>

                        <div class="hoosh-box">
                            <div style="display:flex;align-items:center;gap:16px;margin-bottom:18px;">
                                <img src="https://ehsandibazar.com/public/storage/files/shares/ehsan-profile.jpg" alt="احسان دیبازر" style="width:60px;height:60px;border-radius:50%;object-fit:cover;object-position:top;border:2px solid #d9bb75;flex-shrink:0;">
                                <div>
                                    <div style="font-size:16px;font-weight:800;color:#fff;">سلام، من احسان دیبازر هستم</div>
                                    <div style="font-size:12px;color:#d9bb75;margin-top:3px;">مربی هنرهای رزمی و دفاع شخصی | کارشناس ارشد علوم ورزشی | توسعه‌دهنده مفهوم هوش رزمی</div>
                                </div>
                            </div>
                            <p>سال‌هاست زندگی من با ورزش‌های رزمی گره خورده؛ اما چیزی که همیشه بیشتر از مدال، مدرک یا مسابقه برایم اهمیت داشته، رشد انسان‌ها بوده است.</p>
                            <p>من باور دارم هنرهای رزمی فقط برای مبارزه نیستند. اگر درست آموزش داده شوند، اعتمادبه‌نفس می‌سازند، قدرت تصمیم‌گیری را بالا می‌برند، ذهن را تحت فشار آرام‌تر می‌کنند و به آدم‌ها یاد می‌دهند در موقعیت‌های سخت بهتر عمل کنند.</p>
                            <p>امروز تمام این تجربه‌ها را در یک مسیر واحد به کار گرفته‌ام؛ مسیری که آن را <strong>«هوش رزمی»</strong> می‌نامم — توانایی حفظ آرامش زیر فشار، تصمیم‌گیری درست، کنترل ذهن و محافظت مؤثر از خود و عزیزانت.</p>
                            <a href="https://ehsandibazar.com/about-us"
                               onclick="gtag('event','click',{event_category:'outbound',event_label:'احسان_دیبازر',value:1});"
                               class="hoosh-btn">
                                با احسان دیبازر بیشتر آشنا شوید
                            </a>
                        </div>

                    </article>

                    @if(isset($similarArticles) && count($similarArticles) > 0)
                        <div class="site-blog-post__related">
                            <div class="site-blog__sidebar__item__header">
                                <fieldset>
                                    <legend>
                                        مطالب مرتبط
                                    </legend>
                                </fieldset>
                            </div>
                            <div class="row">
                                @foreach($similarArticles as $itemArticle)
                                    <div class="col-12 col-md-4">
                                        <div class="site-blog__posts__item">
                                            <div class="site-blog__posts__item__image">
                                                <a href="{{ $itemArticle->path() }}">
                                                    <img src="{{ isset($itemArticle->image[0]) ? url($itemArticle->image[0]->url) : null }}"
                                                         alt="{{ $itemArticle->title }}" loading="lazy">
                                                </a>
                                                @if($itemArticle->categories->count())
                                                <div class="site-blog__posts__item__cat">
                                                    <span class="site-blog__posts__item__cat__label">
                                                        {{ $itemArticle->categories[0]->title }}
                                                    </span>
                                                </div>
                                                @endif
                                            </div>
                                            <div class="site-blog__posts__item__desc">
                                                <h4 class="site-blog__posts__item__desc__title">
                                                    <a href="{{ $itemArticle->path() }}">
                                                        {{ $itemArticle->title }}
                                                    </a>
                                                </h4>
                                                <ul class="site-blog__posts__item__desc__list">
                                                    @if($itemArticle->user->first_name)
                                                    <li>
                                                        {{ $itemArticle->user->first_name }}
                                                    </li>
                                                    @endif
                                                    <li>
                                                        {{ $itemArticle->created_at }}
                                                    </li>
                                                </ul>
                                                <p class="site-blog__posts__item__desc__detail">
                                                    {!! \Illuminate\Support\Str::limit(strip_tags($itemArticle->body),100) !!}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>

                    @if(isset($lastArticles) && count($lastArticles) > 0)
                        <div class="col-12 col-md-4">
                            <div class="site-blog__sidebar__item__header">
                                <fieldset>
                                    <legend>
                                        آخرین مطالب
                                    </legend>
                                </fieldset>
                            </div>
                            <div class="site-blog-post__last">
                                @foreach($lastArticles as $itemLastArticle)
                                    <div class="site-blog-post__last__item">
                                        <div class="row">
                                            <div class="col-4">
                                                <div class="site-blog-post__last__item__image">
                                                    <a href="{{ $itemLastArticle->path() }}">
                                                        <img src="{{ isset($itemLastArticle->image[0]) ? url($itemLastArticle->image[0]->url) : null }}"
                                                             alt="{{ $itemLastArticle->title }}" loading="lazy">
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-8">
                                                <div class="site-blog-post__last__item__desc">
                                                    <h5>
                                                        <a href="{{ $itemLastArticle->path() }}">{{ $itemLastArticle->title }}</a>
                                                    </h5>
                                                    <ul>
                                                        <li class="badge badge-danger">
                                                            <i class="fas fa-date"></i>
                                                            <span>{{ $itemLastArticle->created_at }}</span>
                                                        </li>

                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

            </div>
        </div>
    </div>
</main>

@endsection

@section('site-json-ld')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebPage",
    "name": "{{ $page->title }}",
    "url": "{{ url($page->path()) }}",
    "inLanguage": "fa-IR",
    "publisher": {
        "@type": "Person",
        "name": "احسان دیبازر",
        "url": "https://ehsandibazar.com"
    }
}
</script>
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
        {"@type":"ListItem","position":1,"name":"صفحه اصلی","item":"https://ehsandibazar.com/"},
        {"@type":"ListItem","position":2,"name":"{{ $page->title }}","item":"{{ url($page->path()) }}"}
    ]
}
</script>
@endsection

@section('site-js')
<script>
(function(){
    var bar = document.getElementById('reading-progress');
    if(!bar) return;
    function update(){
        var s = window.pageYOffset||document.documentElement.scrollTop;
        var d = document.documentElement.scrollHeight - window.innerHeight;
        bar.style.width = (d>0?(s/d*100):0)+'vw';
        requestAnimationFrame(update);
    }
    requestAnimationFrame(update);
})();

var tocList = document.getElementById('toc-list');
if (tocList) {
    tocList.querySelectorAll('a').forEach(function(a) {
        a.addEventListener('click', function(e) {
            e.preventDefault();
            var target = document.querySelector(a.getAttribute('href'));
            if (target) target.scrollIntoView({behavior:'smooth'});
        });
    });
}

function copyLink(btn){
    var url=btn.getAttribute('data-url');
    if(navigator.clipboard){navigator.clipboard.writeText(url).then(function(){showCopied(btn);});}
    else{var ta=document.createElement('textarea');ta.value=url;document.body.appendChild(ta);ta.select();document.execCommand('copy');document.body.removeChild(ta);showCopied(btn);}
}
function showCopied(btn){
    btn.classList.add('copied');
    btn.innerHTML='<i class="fas fa-check"></i> کپی شد!';
    setTimeout(function(){btn.classList.remove('copied');btn.innerHTML='<i class="fas fa-link"></i> کپی لینک';},2500);
}
</script>
@endsection