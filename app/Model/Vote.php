<?php

namespace App\Model;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vote extends Model
{
    use SoftDeletes,CascadeSoftDeletes;
    protected $fillable = [
        'question_id',
        'answer_id',
        'ip'
    ];

    public function answer()
    {
        return $this->belongsTo(Answer::class);
    }
}
