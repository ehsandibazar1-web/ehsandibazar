<?php

namespace App\Model;

use App\Traits\HasComment;
use App\Traits\HasFollow;
use App\User;
use Iatstuti\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use HasComment,SoftDeletes,CascadeSoftDeletes;
    protected $fillable = [
        'user_id',
        'question',
        'title',
        'state'
    ];

    protected $cascadeDeletes = ['answers'];
    protected $dates = ['deleted_at'];

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function scopeOwner($query)
    {
        if (auth()->user()->isAdmin() || auth()->user()->isSuperAdmin()) {
            return $query;
        } else {
            return $query->where('user_id', auth()->id());
        }
    }



}
