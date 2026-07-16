@extends('site.layout.master')
@section('content')
    @if(isset($sliders) && !empty($sliders))
        <!--اسلایدر-->
        <section class="container-fluid p-0 slider">
            <div class="row">
                <div class="col-12 slider p-0">
                    <div class="owl-carousel owl-theme owl-slider">
                        @foreach($sliders as $slider)
                            <a class="item" href="{{ $slider->code }}">
                                <img src="{{ $slider->code5 }}" alt="{{ $slider->name }}">
                                <div class="owl-slide-text">
                                    <div class="owl-slide-animated logo-slider  d-block w-100">
                                        {{--                                        <img src="{{ $slider->code5 }}" class="img-fluid" alt="{{ $slider->name }}">--}}
                                    </div>
                                    <div class="owl-slide-animated main-text-slider  d-block w-100 mt-3">
                                        {{ $slider->name }}
                                    
                                    </div>
                                     <div class="owl-slide-animated lnk-slide2 main-text-slider2 color-slide  d-block w-100 mt-2">
                                           
                                         & 
                                 
                                    </div>
                                    <div class="owl-slide-animated lnk-slide2 main-text-slider2 color-slide  d-block w-100 mt-2">
                                
                                        {{ $slider->code2 }}
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!--محتوا-->
    @if(isset($boxSlider) && !empty($boxSlider))
        <section class="container-fluid send-section">
            <div class="container p-0">
                <div class="row row-video">
                    <div class="col-md-12 mx-auto col-12 p-0">
                        <div class="owl-carousel owl-theme owl-send">
                            @foreach($boxSlider as $item)
                                <div class="item wow fadeInUp">
                                    <div class="js-video-button video{{$item->id}} position-relative"
                                         data-channel="custom">
                                        <span class="video-icon"></span>
                                        <img src="{{ $item->code5 }}">
                                        <div class="text-video">
                                            {{ $item->name }}
                                        </div>
                                    </div>
                                    <script>
                                        $(".video{{$item->id}}").modalVideo({
                                            youtube: {
                                                controls: 0,
                                                nocookie: true
                                            },
                                            url: "{{ $item->code }}"
                                        });
                                    </script>

                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    @if(isset($textAbout) && !empty($textAbout))
        <section class="container-fluid  about-section p-0 position-relative">
            <div class="container p-0">
                <div class="row  align-items-center">
                    <div class="col-md-6 pl-md-0 col-12">
                        <div class="row">
                            <div class="col-md-10 col-12 p-0">
                                <h1 class="abou-company wow fadeInUp">{{ $textAbout->name }}</h1>
                                <div class="d-block sub-title wow fadeInUp">
                                    {{ $textAbout->code }}
                                </div>
                                <div class="d-block about-text mt-3 mb-2 wow fadeInUp">
                                    {!! strip_tags($textAbout->code4) !!}
                                </div>
                                <div class="d-block mt-5 wow fadeInUp text-center-mob">
                                    <a href="{{ $textAbout->code3 }}" class="show-more">
                                        {{ $textAbout->code2 }}
                                        <i class="fal fa-long-arrow-left"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 pr-md-0 col-12 p-0">

                    </div>
                </div>
            </div>
            <img src="{{ $textAbout->code5 }}" class="img-fluid img-about wow fadeInUp" alt="{{ $textAbout->name }}">
        </section>
    @endif

    @if(isset($course) && !empty($course))
        <section class="container-fluid counter">
            <div class="container">
                <div class="row">
                    <h2 class="col-12 text-center title-counter wow fadeInUp">
                        {{ $course->name }}
                    </h2>
                    <div class="col-12 text-center sun-counter wow fadeInUp">
                        {{ $course->description }}
                    </div>
                </div>
                @if(isset($course->systeminfmanage) && !empty($course->systeminfmanage))
                    <div class="row mt-4">
                        <div class="col-12 p-0">
                            <div class="owl-carousel owl-theme owl-learn">
                                @foreach($course->systeminfmanage as $item)
                                    <div class="item wow fadeInUp">
                                        <a href="{{ $item->code }}" class="d-inline-block l-box">
                                            <div class="d-block img-learn">
                                                <img src="{{ $item->code5 }}" class="img-fluid"
                                                     alt="{{ $item->name }}">
                                            </div>
                                            <div class="d-block l-title text-center">
                                                {{ $item->name }}
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </section>
    @endif

    @if(isset($articles) && !empty($articles))
        <section class="container-fluid section-news">
            <div class="container p-0">
                <div class="row">
                    <div class="col-12 p-0">
                        <h3 class="title-section text-center wow fadeInUp">Educational content</h3>
                        <div class="sub-title-section text-center mt-2 wow fadeInUp">
                            <a href="{{ route('site.article') }}">
                                View all archives
                                <i class="fal fa-long-arrow-left"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12 p-0">
                        <div class="owl-carousel owl-theme owl-news">
                            @foreach($articles as $article)
                                <a class="item wow fadeInUp" href="{{ $article->path() }}">
                                    <div class="d-block img-news">
                                        @if(isset($article->image[0]) && !empty($article->image[0]))
                                            <img src="{{ $article->image[0]->url }}" class="img-fluid"
                                                 alt="{{ $article->title }}">
                                        @endif
                                    </div>
                                    <div class="d-block title-news">
                                        {{ $article->title }}
                                    </div>
                                    <div class="d-block news-short-text">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($article->body),200) }}
                                    </div>
                                    <div class="d-block  text-end">
									<span class="more-news">
										Read more
									</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    @if(isset($resultsMembers) && !empty($resultsMembers))
        <section class="container-fluid result-section">
            <div class="container p-0">
                <div class="row align-items-center">
                    <div class="col-md-6 ps-md-0 col-12">
                        <div class="row">
                            <div class="col-md-10 col-12 p-0">
                                <div class="abou-company wow fadeInUp">{{ $resultsMembers->name }}
                                </div>
                                <div class="d-block sub-title wow fadeInUp">
                                    {{ $resultsMembers->description }}
                                </div>
                                <div class="d-block mt-5 text-center-mob">
                                    <a href="https://ehsandibazar.com/en/page/results-members" class="show-more wow fadeInUp">
                                        See all member results
                                        <i class="fal fa-long-arrow-left"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 pe-md-0 col-12">
                        @if(isset($resultsMembers->systeminfmanage) && !empty($resultsMembers->systeminfmanage))
                            <ul class="user-list p-0 mt-xs-15">
                                @foreach($resultsMembers->systeminfmanage as $item)
                                    <li class="wow fadeInUp js-video-button video{{ $loop->iteration }} position-relative"
                                        data-channel="custom">
                                        <div class="d-block img-user">
                                            <img src="{{ $item->code5 }}" class="img-fluid"
                                                 alt="{{ $item->name }}">
                                        </div>
                                        <div class="d-block text-center mt-2">
                                            {{ $item->name }}
                                        </div>
                                        <script>
                                            $(".video{{ $loop->iteration }}").modalVideo({
                                                youtube: {
                                                    controls: 0,
                                                    nocookie: true
                                                },
                                                url: "{{ $item->code }}"
                                            });
                                        </script>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    @endif

    @if(isset($instagram) && !empty($instagram))
        <section class="container-fluid inst2 position-relative">
            <div class="row">
                <div class="col-md-6 col-12 ps-md-0 order-lg-1">
                    <div class="bg-ins">
                        @if(isset($instagram[0]) && !empty($instagram[0]))
                            <img src="{{ $instagram[0]->code5 }}" class="img-fluid" alt="{{ $instagram[0]->name }}">
                        @endif
                    </div>
                </div>
                <div class="col-md-6 col-12 pe-md-0 order-lg-0">
                    <div class="row align-items-center ">
                        <div class="col-lg-6 col-md-5 col-12">
                        </div>
                        <div class="col-lg-6 col-md-7 col-12 text-center insta-link">
                            @if(isset($instagram[1]) && !empty($instagram[1]))
                                <div class="d-block wow fadeInUp">
                                    <a href="{{ $instagram[1]->code }}" class="d-inline-block"><img
                                                src="{{ $instagram[1]->code5 }}" alt="{{ $instagram[1]->name }}"
                                                class="img-fluid"></a>
                                </div>
                                <div class="d-block text-link mt-3">
                                    <a href="{{ $instagram[1]->code }}" class="d-inline-block wow fadeInUp">
                                        {{ $instagram[1]->name }}
                                    </a>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </section>
        <section class="container-fluid inst position-relative">
            <div class="row">
                <div class="col-md-6 col-12 ps-md-0">
                    <div class="bg-ins">
                        @if(isset($instagram[2]) && !empty($instagram[2]))
                            <img src="{{ $instagram[2]->code5 }}" class="img-fluid" alt="{{ $instagram[2]->name }}">
                        @endif
                    </div>
                </div>
                <div class="col-md-6 col-12 pe-md-0">
                    <div class="row align-items-center">
                        <div class="col-lg-6 col-md-7 col-12 text-center insta-link">
                            @if(isset($instagram[3]) && !empty($instagram[3]))
                                <div class="d-block">
                                    <a href="{{ $instagram[3]->code }}" class="d-inline-block wow fadeInUp"><img
                                                alt="{{ $instagram[3]->name }}"
                                                src="{{ $instagram[3]->code5 }}" class="img-fluid"></a>
                                </div>
                                <div class="d-block text-link mt-3">
                                    <a href="{{ $instagram[3]->code }}" class="d-inline-block wow fadeInUp">
                                        {{ $instagram[3]->name }}
                                    </a>
                                </div>
                            @endif
                        </div>
                        <div class="col-lg-6 col-md-5 col-12">

                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
@endsection


