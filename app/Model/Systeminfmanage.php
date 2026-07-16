<?php

namespace App\Model;

use App\Traits\HasImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Systeminfmanage extends Model
{
    use HasImage, SoftDeletes;

    protected $fillable = [
        'name', 'code', 'code2', 'code3', 'code4', 'code5', 'systeminf_id', 'status'
    ];

    public function Systeminf()
    {
        return $this->belongsTo(Systeminf::class);
    }


    /* owner */
    public function scopeOwner($query)
    {
        if (auth()->user()->isAdmin() || auth()->user()->isSuperAdmin()) {
            return $query;
        } else {
            return $query->where('user_id', auth()->id());
        }
    }

    public function getCode5Attribute($value)
    {
        return env('APP_URL') . $value;
    }

    public function setCode5Attribute($value)
    {
        $this->attributes['code5'] = str_replace(env('APP_URL'), "", $value);
    }

}
