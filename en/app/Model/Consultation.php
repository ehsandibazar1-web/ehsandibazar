<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Consultation extends Model
{
    use SoftDeletes;
    protected $guarded = [''];

    public function getCreatedAtAttribute($value)
    {
        $v = verta($value);
        return $this->attributes['created_at'] = $v->format('%d %B %Y - H:i');
    }
}
