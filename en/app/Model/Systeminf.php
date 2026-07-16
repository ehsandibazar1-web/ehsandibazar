<?php

namespace App\Model;

use App\Traits\HasSeo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Systeminf extends Model
{
    use SoftDeletes,HasSeo;
    protected $fillable = [
        'name', 'description', 'status'
    ];

    public function systeminfmanage()
    {
        return $this->hasMany(Systeminfmanage::class);
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
}
