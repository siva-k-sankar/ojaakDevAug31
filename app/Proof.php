<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proof extends Model
{
    protected $fillable = [
        'user_id',
        'image',
        'verified',
        'verified_date',
        'proof',
        'comments',
        'verified_by','uuid',
    ];
}
