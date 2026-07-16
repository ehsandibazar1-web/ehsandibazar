<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image extends Model
{
    use SoftDeletes;
    public static $preventAttrSet = true;
    protected $fillable = [
        'url' , 'imageable_type' , 'imageable_id' , 'user_id'
    ];

    public function imageable()
    {
        return $this->morphTo();
    }

    /* scope */
    public function scopeOwner($query)
    {
        if (auth()->user()->isAdmin() || auth()->user()->isSuperAdmin()) {
            return $query;
        } else {
            return $query->where('user_id', auth()->id());
        }
    }

    public function getUrlAttribute($value)
    {
        if (self::$preventAttrSet){
            return env('APP_URL').$value;
        }
        return $value;
    }

    public function setUrlAttribute($value)
    {
        $this->attributes['url'] = str_replace(env('APP_URL'),"",$value);
    }
}
