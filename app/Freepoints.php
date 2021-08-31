<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Freepoints extends Model
{
   protected $fillable = [
        'user_id',
        'description',
        'point',
        'status',
        'used',
        'ads_id',
        'expire_date',
        'order_id'
    ];
}
