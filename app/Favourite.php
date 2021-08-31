<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Favourite extends Model
{
    protected $table = "favourites";
    protected $fillable = [
        'user_id', 'ads_id','uuid'
    ];
}
