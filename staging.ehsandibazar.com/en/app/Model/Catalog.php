<?php

namespace App\Model;

use App\Traits\HasImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Catalog extends Model
{
    use SoftDeletes,HasImage;

    protected $fillable = [
        'url' , 'catalogable_type' , 'catalogable_id' , 'user_id'
    ];

    public function catalogable()
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
}
