<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Province extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];


    protected $fillable = [
        'name', 'longitude','latitude'
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function($province) {
            foreach ($province->city()->get() as $city) {
                $city->delete();
            }
        });

        static::deleting(function($province) {
            foreach ($province->address()->get() as $address) {
                $address->delete();
            }
        });
    }

    public function city()
    {
        return $this->hasMany(City::class);
    }

    public function address()
    {
        return $this->hasMany(Address::class);
    }

}
