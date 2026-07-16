<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;

    protected $table = 'comments';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'user_id','parent_id','comment','commentable_type','commentable_id','status','ip'
    ];

     public function commentable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(Comment::class,'parent_id');
    }

    public function getCreatedAtAttribute($value)
    {
        $v = verta($value);
        switch (app()->getLocale()) {
            case('fa');
                return $this->attributes['created_at'] = $v->formatGregorian('H:i Y/m/d ');
            case('en');
                return $this->attributes['created_at'] = $v->format('%d %B %Y H:i');

        }
    }

    public function setCommentAttribute($value)
    {
        $this->attributes['comment'] = str_replace(PHP_EOL , "<br>" , $value);
    }

}
