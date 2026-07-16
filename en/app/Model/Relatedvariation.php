<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Relatedvariation extends Model
{
    protected $fillable =[
        'variation_id',
        'attribute_type_value_id',
    ];

    public function variation()
    {
        return $this->belongsTo(Variation::class,'variation_id','id');
    }

    public function attributeTypeValue()
    {
        return $this->belongsTo(AttributeTypeValue::class,'attribute_type_value_id');
    }
}
