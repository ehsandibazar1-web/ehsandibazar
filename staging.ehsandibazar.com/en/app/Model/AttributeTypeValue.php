<?php

namespace App\Model;

use App\User;
use Iatstuti\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttributeTypeValue extends Model
{
    use SoftDeletes,CascadeSoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'attribute_type_id',
        'user_id',
        'value',
        'label',
        'status',
        'lang',
        'color'
    ];

    protected $cascadeDeletes = ['variations'];

    /* scope */
    public function scopeOwner($query)
    {
        if (auth()->user()->isAdmin() || auth()->user()->isSuperAdmin()) {
            return $query;
        } else {
            return $query->where('user_id', auth()->id());
        }
    }

    /* relation with attribute type */
    public function attributeType()
    {
        return $this->belongsTo(AttributeType::class, 'attribute_type_id', 'id');
    }

    /* relation with user */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /* related with variation */
    public function variations()
    {
        return $this->hasMany(Variation::class,'attribute_type_value_id');
    }

    public function relatedVariations()
    {
        return $this->hasMany(Relatedvariation::class,'attribute_type_value_id');
    }
}
