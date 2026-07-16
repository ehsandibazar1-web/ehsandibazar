<?php

namespace App\Model;

use App\Traits\HasImage;
use App\Traits\HasVideo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exam extends Model
{
    use SoftDeletes,HasVideo,HasImage;

    protected $guarded = [];

    public function getCreatedAtAttribute($value)
    {
        $v = verta($value);
        return $this->attributes['created_at'] = $v->format('%d %B %Y - H:i');
    }
}
