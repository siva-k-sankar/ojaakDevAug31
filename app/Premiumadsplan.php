<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Premiumadsplan extends Model
{
    protected $table = 'premiumadsplans';
   	protected $fillable = ['uuid','category','plan_name','validity','ads_points','discount','comments','status'];
}
