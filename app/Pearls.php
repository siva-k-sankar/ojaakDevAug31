<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pearls extends Model
{
    protected $table = 'pearl_ads';
    protected $fillable = ['ads_id','expire_date','user_id','category_id'];
}
