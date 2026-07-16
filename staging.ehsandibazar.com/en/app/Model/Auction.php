<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Auction extends Model
{
    use SoftDeletes;
    protected $fillable = ['product_id', 'start_date', 'start_price', 'end_price', 'click_count',
        'every_click_price', 'every_click_price_for_pay', 'participant_count', 'status'];

    protected $dates = ['deleted_at'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function suggestion()
    {
        return $this->hasMany(Suggestion::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function auctionResult()
    {
        return $this->hasMany(AuctionResult::class);
    }


}
