<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaidAds extends Model
{
    //
   protected $table = 'paidadsplan';
   protected $fillable = ['uuid','category','plan_name','validity','wallet_points','quantity_1','quantity_3','quantity_5','quantity_10','discount','comments'];
}
