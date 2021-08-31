<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City_requests extends Model
{
   	protected $table ="city_request";
    protected $fillable = ['name','uuid','user_id','post_id'];
}
