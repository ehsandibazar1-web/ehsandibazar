<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttributeValue extends Model
{
   /* use SoftDeletes;*/

    protected $table = 'attribute_values';

    protected $fillable = [
        'attribute_id',
        'category_id',
        'user_id',
        'value'
    ];
    /*protected  $dates =['deleted_at'];*/


    /* many to many relation by products  pTable :attribute_value_product  */
    public function products()
    {
        return $this->belongsToMany(Product::class , 'attribute_value_product','attribute_value_id','product_id');
    }

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    public function categoryproduct()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }




}
