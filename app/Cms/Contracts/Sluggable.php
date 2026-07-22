<?php

namespace App\Cms\Contracts;

interface Sluggable
{
    public function getSlug(): string;

    public static function makeSlug(string $title, ?string $locale = null): string;
}
