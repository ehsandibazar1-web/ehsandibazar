<?php

namespace App\Model;

use App\Traits\HasComment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Overtrue\LaravelLike\Traits\Likeable;

class File extends Model
{
    use SoftDeletes,HasComment,Likeable;
    protected $fillable = [
        'url' , 'fileable_type' , 'fileable_id' , 'user_id','suffix'
    ];

    public function fileable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
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
}
