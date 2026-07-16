<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Detail extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'user_id', 'store_name', 'sheba_number', 'account_number',
        'cart_number', 'national_code', 'postal_code','supply',
        'extra_description'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
