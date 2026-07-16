<?php


namespace App\Utility;


use App\Model\Article;
use App\Model\Page;
use App\Model\Product;


class CategoryType
{
    public static function types()
    {
        return [
            Article::class => "مقالات",
            Product::class => "محصولات",
            Page::class => "صفحات",
        ];
    }

}