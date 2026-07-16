<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attribute extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table = 'attributes';
    protected $fillable = [
        'name',
        'user_id',
        'attribute_group_id',
        'label',
        'status' ,
        'is_filter'
    ];

    // this is a recommended way to declare event handlers
    public static function boot() {
        parent::boot();

        static::deleting(function($user) { // before delete() method call this
            $user->attributevalue()->delete();
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

    public function attributeGroup() // attribute_group_id
    {
        return $this->belongsTo(AttributeGroup::class, 'attribute_group_id', 'id');
    }

    public function categoryProducts()
    {
//        return $this->belongsToMany(Category::class, 'attribute_category', 'attribute_id', 'category_id');
        return $this->belongsToMany(Category::class);
    }

    public function attributevalue()
    {
        return $this->hasMany(AttributeValue::class);
    }


}
