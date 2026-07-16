<?php

namespace App\Model;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Answer extends Model
{
    use SoftDeletes,CascadeSoftDeletes;
    protected $fillable =[
        'title' ,
        'question_id'
    ];

    protected $dates = ['deleted_at'];
    protected $cascadeDeletes = ['votes'];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

}
