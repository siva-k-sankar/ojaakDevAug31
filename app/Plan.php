<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
	protected $table = 'plans';
    protected $fillable = ['uuid','plan_name','plan_price','plan_Ads','created_by','status'];
}
