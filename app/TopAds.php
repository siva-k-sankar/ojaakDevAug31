<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TopAds extends Model
{
    //
    protected $table = 'top_ads_plan';
     protected $fillable = ['uuid','category','name','validity_7','validity_15','validity_30','discount','comments','type'];
}
