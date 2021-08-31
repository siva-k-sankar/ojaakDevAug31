<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Description extends Model
{
    protected $table = 'description_user';
     protected $fillable = [
     	'user_id',
     	'description'
    ];
}
