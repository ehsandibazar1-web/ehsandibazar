<?php

namespace App\Model;

use App\Traits\HasImage;
use App\User;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gift extends Model
{
    use SoftDeletes, HasImage;
    protected $table = 'gift';
    protected $fillable = [
        'user_id', 'product_id', 'name', 'score', 'lang' ,'status'
    ];
    protected $dates = ['deleted_at'];


    /* scope */
    public function scopeOwner($query)
    {
        if (auth()->user()->isAdmin() || auth()->user()->isSuperAdmin()) {
            return $query;
        } else {
            return $query->where('user_id', auth()->id());
        }
    }


    // this is a recommended way to declare event handlers
    public static function boot() {
        parent::boot();

        static::deleting(function($user) { // before delete() method call this
            $user->requestgifts()->delete();
            // do the rest of the cleanup...
        });
    }

    public function requestgifts()
    {
        return $this->hasMany(Requestgift::class);
    }


    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
