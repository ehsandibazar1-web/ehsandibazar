<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttributeGroup extends Model
{
    use SoftDeletes;
    protected  $dates =['deleted_at'];
    protected $table ='attribute_groups';

    protected $fillable =['name' , 'user_id','label', 'status'];

    // this is a recommended way to declare event handlers
    public static function boot() {
        parent::boot();

        static::deleting(function($user) { // before delete() method call this
            $user->attributes()->delete();
            // do the rest of the cleanup...
        });
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

    public function attributes()
    {
        return $this->hasMany(Attribute::class,'attribute_group_id','id');
    }



}
