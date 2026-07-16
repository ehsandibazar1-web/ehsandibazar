<?php

namespace App\Model;

use App\Traits\HasFile;
use App\Traits\HasImage;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use SoftDeletes,HasFile;

    protected $table='ticket';

    protected $fillable = [
        'user_id','subject','body','status','tracking_code','priority','departeman','send_email'
    ];

    protected $dates = ['deleted_at'];

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($item) {
            $item->answer()->delete();
        });
    }

    public function answer()
    {
        return $this->hasMany(TicketAnswer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function departemans()
    {
        return $this->belongsTo(Systeminfmanage::class, 'departeman', 'id');
    }



    /* scope */
    public function scopeOwner($query)
    {
        if (auth()->user()->isAdminOrSuperAdmin()) {
            return $query;
        } else {
            return $query->where('user_id', auth()->id());
        }
    }



}
