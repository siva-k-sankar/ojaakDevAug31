<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdsLimits extends Model
{
    protected $table = 'ads_limits';
    protected $fillable = ['user_id','free','paid',];
}
