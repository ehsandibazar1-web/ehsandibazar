<?php

namespace App\Model;
use App\Traits\HasDiscount;
use App\User;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasDiscount,SoftDeletes;
    protected $fillable = ['name' , 'label'];
    /* scope */
    public function scopeOwner($query)
    {
        if (auth()->user()->isAdmin() || auth()->user()->isSuperAdmin()) {
            return $query;
        } else {
            return $query->where('id', auth()->id());
        }
    }

    public function scopeAdminRole($query)
    {
        if (auth()->user()->isSuperAdmin()) {
            return $query;
        } elseif(auth()->user()->isAdmin()) {
            return $query->where('id' , ">" , 1);
        }else{
            return $query->where('id', auth()->id());
        }
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function path()
    {
        return $this->label;
    }
}
