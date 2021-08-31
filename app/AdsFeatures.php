<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdsFeatures extends Model
{
    protected $table = 'ads_features';
    protected $fillable = ['ads_id','expire_date','user_id'];
}
