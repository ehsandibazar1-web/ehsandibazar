<?php

namespace App\Model;

use App\User;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttributeType extends Model
{
    use SoftDeletes , CascadeSoftDeletes;
    protected  $dates =['deleted_at'];
    protected $table = 'attribute_types';
    protected $fillable =[
        'name',
        'user_id',
        'label',
        'status'
    ];

    protected $cascadeDeletes = ['attributeTypeValue'];

    /* scope */
    public function scopeOwner($query)
    {
        if (auth()->user()->isAdmin() || auth()->user()->isSuperAdmin()) {
            return $query;
        } else {
            return $query->where('user_id', auth()->id());
        }
    }

    /* relation with attribute type value */
    public function attributeTypeValue()
    {
        return $this->hasMany(AttributeTypeValue::class,'attribute_type_id','id');
    }

    /* relation with user */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
