<?php

namespace App\Model;

use App\User;
use App\Traits\HasFile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketAnswer extends Model
{
    use SoftDeletes,HasFile;

    protected $table='ticket_answers';

    protected $fillable = [
        'ticket_id' , 'answer','user_id','status'
    ];

    protected $dates = ['deleted_at'];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
