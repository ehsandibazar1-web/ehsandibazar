<?php

namespace App\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Database\Eloquent\Model;

class NewsLatters extends Model
{
    use SoftDeletes;

    protected $table='newslatters';

    protected $guarded = ['id'];



}
