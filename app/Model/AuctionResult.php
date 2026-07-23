<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuctionResult extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];

    public function auction()
    {
        return $this->belongsTo(Auction::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function scopeWinner($query)
    {
        if (auth()->user()->isAdmin() || auth()->user()->isSuperAdmin()) {
            return $query;
        } else {
            return $query->where('user_id', auth()->id());
        }
    }

    public function getCreatedAtAttribute($value)
    {
        $v = verta($value);
        switch (app()->getLocale()) {
            case('fa');
                return $v->format('%d %B %Y H:i:s');
            case('en');
                return $v->formatGregorian('d m Y H:i:s');
        }
    }

}
