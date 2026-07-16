<?php

namespace App\Model;

use App\User;
use App\Utility\Level;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Address extends Model
{
    use SoftDeletes;
    protected  $dates =['deleted_at'];

    protected $table='address';

    protected $fillable = [
        'user_id', 'name','mobile','tell','province_id','city_id','fullAddress',
        'postal_code','lang'
    ];

    //public $timestamps=false;

    public function province()
    {
        return $this->belongsTo(Province::class,'province_id','id');
    }

    public function city()
    {
        return $this->belongsTo(City::class,'city_id','id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function scopeOwner($query)
    {
        if (auth()->user()->isAdmin() || auth()->user()->isSuperAdmin()) {
            return $query;
        } else {
            return $query->where('user_id', auth()->id());
        }
    }

    public function inTehran($city)
    {
        return $city == 117 ? true : false;

    }
}
