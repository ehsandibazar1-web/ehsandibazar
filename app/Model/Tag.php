<?php

namespace App\Model;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use Sluggable, SoftDeletes;
    protected $table = "tags";
    public $fillable = [
        'title',
        'slug',
        'lang',
        'status'
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function path()
    {
        return url('tag/' . $this->slug);
    }

    public function tagable()
    {
        return $this->hasMany(Tagable::class);
    }

    public function articles()
    {
        return $this->morphedByMany(Article::class, 'taggable');
    }





}
