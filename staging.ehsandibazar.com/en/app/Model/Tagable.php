<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tagable extends Model
{
    use SoftDeletes;
    protected $table = "taggables";
    protected $fillable = ['tag_id', 'taggable_id', 'taggable_type'];
    protected $dates = ['deleted_at'];

    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }

    public function taggable()
    {
        return $this->morphTo();
    }

}
