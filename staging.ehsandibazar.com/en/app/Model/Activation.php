<?php

namespace App\Model;

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Activation extends Model
{
    protected $fillable = [

        'user_id', 'code', 'used', 'expire' , 'mobile'

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeCreateCode($query, $user)
    {
        $code = $this->code();
        return $query->create([
            'user_id' => $user->id,
            'code' => $code,
            'expire' => Carbon::now()->addMinutes(15)
        ]);
    }

    private function code()
    {
        do {
            $code = Str::random(60);
            $checkCode = static::whereCode($code)->get();
        } while (!$checkCode->isEmpty());
        return $code;
    }

    public function scopeCreatecodesms($query, $user)
    {
        $code = $this->codesms();
        return $query->create([
            'user_id' => $user->id,
            'code' => $code,
            'mobile' => $user->email,
            'expire' => Carbon::now()->addMinutes(15)
        ]);
    }

    private function codesms()
    {
        do {
            $code = mt_rand(1000, 9999);
            $checkCode = static::whereCode($code)->get();
        } while (!$checkCode->isEmpty());
        return $code;
    }
}
