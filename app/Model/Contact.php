<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use SoftDeletes;
    protected  $dates =['deleted_at'];

    protected $table = 'contact';

    protected $fillable = [
        'name', 'email', 'body', 'lang', 'ip', 'status','user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getCreatedAtAttribute($value)
    {
        $v = verta($value);
        return $v->format('%d %B %Y - H:i');
    }
}
