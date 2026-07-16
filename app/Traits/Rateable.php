<?php

namespace App\Traits;

use App\Model\Rating;

/**
 * Lightweight replacement for the abandoned willvincent/laravel-rateable
 * package. Operates on the existing `ratings` table
 * (columns: user_id, rating, rateable_id, rateable_type).
 */
trait Rateable
{
    /**
     * All ratings for this model.
     */
    public function ratings()
    {
        return $this->morphMany(Rating::class, 'rateable');
    }

    /**
     * Average rating value (null when there are no ratings).
     */
    public function getAverageRatingAttribute()
    {
        return $this->ratings()->avg('rating');
    }

    /**
     * Total number of ratings.
     */
    public function getTimesRatedAttribute()
    {
        return $this->ratings()->count();
    }

    /**
     * Sum of all rating values.
     */
    public function getSumRatingAttribute()
    {
        return $this->ratings()->sum('rating');
    }
}
