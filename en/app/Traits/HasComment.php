<?php


namespace App\Traits;


use App\Model\Comment;

trait HasComment
{
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}