@extends('site.layout.master')
@section('site.css')
    <link rel="stylesheet" type="text/css" href="{{ url('') }}/site_theme/css/internal/style.css"/>
@endsection
@section('content')
    <main class="site-blog-post wrapper default">
        <div class="container">
            <div class="site-blog-post__path">
                <ul>
                    <li>
                        <a href="{{ route('site.index') }}">home</a>
                    </li>
                    <li>
                        <a href="#">
                            {{ $article->categories[0]->title }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ $article->path() }}">
                            {{ $article->title }}
                        </a>
                    </li>
                </ul>
            </div>
            <div class="site-blog-post__box">
                <div class="row">
                    <div class="col-12 col-md-8">

                        <div class="site-blog-post__box__body">
                            <div class="site-blog-post__box__body__image">
                                <img src="{{ isset($article->image[0]) ? url($article->image[0]->url) : null }}"
                                     alt="{{ $article->title }}">
                            </div>
                            @if(isset($article->tags))
                            <div class="site-blog-post__box__body__cat">
                                <ul>
                                    @foreach($article->tags as $tag)
                                    <li class="badge badge-info">
                                        <a href="{{ $tag->path() }}">{{ $tag->title }}</a>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                            <div class="site-blog-post__box__body__title">
                                <h1>{{ $article->title }}</h1>
                            </div>
                            <div class="site-blog-post__box__body__info">
                                <ul>
                                    <li class="badge badge-pill badge-dark">
                                        <i class="fas fa-user"></i>
                                        <span>{{ $article->user->name }}</span>
                                    </li>
                                    <li class="badge badge-pill badge-dark">
                                        <i class="fas fa-date"></i>
                                        <span>{{ $article->created_at }}</span>
                                    </li>
                                    <li class="badge badge-pill badge-dark">
                                        <i class="fas fa-comment"></i>
                                        <span>{{ $article->commentCount }}</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="site-blog-post__box__body__detail">
                                <p>{!! $article->body !!}</p>
                            </div>

                            @include('site.product.partials.comment')
                        </div>

                        @if(isset($similarArticles) && count($similarArticles) > 0)
                            <div class="site-blog-post__related">
                                <div class="site-blog__sidebar__item__header">
                                    <fieldset>
                                        <legend>
                                           related articles
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
                                                             alt="{{ $itemArticle->title }}">
                                                    </a>
                                                    <div class="site-blog__posts__item__cat">
                                            <span class="site-blog__posts__item__cat__label">
                                                 {{ $itemArticle->categories[0]->title }}
                                            </span>
                                                    </div>

                                                </div>
                                                <div class="site-blog__posts__item__desc">
                                                    <h4 class="site-blog__posts__item__desc__title">
                                                        <a href="{{ $itemArticle->path() }}">
                                                            {{ $itemArticle->title }}
                                                        </a>
                                                    </h4>
                                                    <ul class="site-blog__posts__item__desc__list">
                                                        <li>
                                                            {{ $itemArticle->user->first_name }}
                                                        </li>

                                                        <li>
                                                            {{ $itemArticle->created_at }}
                                                        </li>
                                                    </ul>
                                                    <p class="site-blog__posts__item__desc__detail">
                                                        {{ \Illuminate\Support\Str::limit(strip_tags($itemArticle->body),100) }}

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
                                      latest articles
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
                                                             alt="{{ $itemLastArticle->title }}">
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="col-8">
                                                <div class="site-blog-post__last__item__desc">
                                                    <h6>
                                                        <a href="{{ $itemLastArticle->path() }}">{{ $itemLastArticle->title }}</a>
                                                    </h6>
                                                    <ul>
                                                        <li class="badge badge-danger">
                                                            <i class="fas fa-date"></i>
                                                            <span>{{ $itemLastArticle->created_at }}</span>
                                                        </li>
                                                        <li class="badge badge-dark">
                                                            <i class="fas fa-comment"></i>
                                                            <span>{{ $itemLastArticle->commentCount }}</span>
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
