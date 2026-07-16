<?php

namespace App\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name' , 'label' ,'method' , 'deleted_at'
    ];

    public function scopePermissionAdmin($query)
    {
        if (auth()->user()->isSuperAdmin()) {
            return $query->orderBy('id', 'desc');
        } else {
            return $query->whereNotIn('name',config('whiteRoute.adminRoute'))->orderBy('id', 'desc');
        }
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
