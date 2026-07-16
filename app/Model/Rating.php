<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $table = 'ratings';

    protected $fillable = [
        'rating',
        'user_id',
        'rateable_id',
        'rateable_type',
    ];

    /**
     * The rated model (Product, Article, Page, ...).
     */
    public function rateable()
    {
        return $this->morphTo();
    }

    /**
     * The user who gave the rating.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
