@extends('site.layout.master')
@section('site.css')
    <link rel="stylesheet" type="text/css" href="{{ url('') }}/site_theme/css/internal/style.css"/>
    @endsection
@section('content')
    <main class="site-blog wrapper default">
        <div class="">
            <div class="container site-blog__box">
                <div class="row">
                    <div class="col-12 col-md-4">
                        <div class="site-blog__sidebar">
                            @if(isset($categorys) && count($categorys) > 0)
                                <div class="site-blog__sidebar__item">
                                    <div class="site-blog__sidebar__item__header">
                                        <fieldset>
                                            <legend>
                                                categories
                                            </legend>
                                        </fieldset>
                                    </div>
                                    <div class="site-blog__sidebar__item__body">
                                        <ul class="site-blog__sidebar__item__body__cat__list">
                                            @foreach($categorys as $item)
                                                <li>
                                                    <a href="{{ $item->path("category-article") }}" class="float-right">{{ $item->title }}</a>
                                                    <span class="badge badge-dark float-left blog_badge_category">{{ $item->article->count() }}</span>
                                                    <span class="clearfix"></span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endif
                            @if(isset($articleViewCount) && count($articleViewCount) > 0)
                                <div class="site-blog__sidebar__item">
                                    <div class="site-blog__sidebar__item__header">
                                        <fieldset>
                                            <legend>
                                                favorites
                                            </legend>
                                        </fieldset>
                                    </div>
                                    <div class="site-blog__sidebar__item__body">
                                        <div class="site-blog__sidebar__item__body__ads__list">
                                            @foreach($articleViewCount as $itemArticle)
                                                <div class="site-blog__sidebar__item__body__ads__list__item">
                                                    <div class="row">
                                                        <div class="col-4">
                                                            <div class="site-blog__sidebar__item__body__ads__list__item__image">
                                                                <a href="{{ $itemArticle->path() }}">
                                                                    <img src="{{ isset($itemArticle->image[0]) ? url($itemArticle->image[0]->url) : '#' }}"
                                                                         alt="{{ $itemArticle->title }}">
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <div class="col-8">
                                                            <div class="site-blog__sidebar__item__body__ads__list__item__desc">
                                                                <h6 class="site-blog__sidebar__item__body__ads__list__item__desc__title">
                                                                    <a href="{{ $itemArticle->path() }}">
                                                                        {{ $itemArticle->title }}
                                                                    </a>
                                                                </h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach

                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-12 col-md-8">
                        <div class="site-blog__posts">
                            <div class="site-blog__sidebar__item__header">
                                <fieldset>
                                    <legend>
                                        Recent Articles
                                    </legend>
                                </fieldset>
                            </div>
                            <div class="">
                                <div class="row">
                                    @if(isset($articles) && count($articles) > 0)
                                        @foreach($articles as $article)
                                            <div class="col-12 col-md-6">
                                                <div class="site-blog__posts__item">
                                                    <div class="site-blog__posts__item__image">
                                                        <a href="{{ $article->path() }}">
                                                                <img src="{{ isset($article->image[0]) ? url($article->image[0]->url) : null }}"
                                                                     alt="{{ $article->title }}">
                                                        </a>
                                                        <div class="site-blog__posts__item__cat">
                                            <span class="site-blog__posts__item__cat__label">
                                                {{ $article->categories[0]->title }}

                                            </span>
                                                        </div>

                                                    </div>
                                                    <div class="site-blog__posts__item__desc">
                                                        <h4 class="site-blog__posts__item__desc__title">
                                                            <a href="{{ $article->path() }}">
                                                                {{ $article->title }}                                                            </a>
                                                        </h4>
                                                        <ul class="site-blog__posts__item__desc__list">
                                                            <li>
                                                                {{ $article->user->first_name }}
                                                            </li>

                                                            <li>
                                                                {{ $article->created_at }}
                                                            </li>
                                                        </ul>
                                                        <p class="site-blog__posts__item__desc__detail">
                                                            {!! \Illuminate\Support\Str::limit(strip_tags($article->body),100) !!}
                                                        </p>

                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>
@endsection
