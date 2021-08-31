<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlansPurchase extends Model
{
    protected $table = 'plans_purchase';
    protected $fillable = ['user_id','uuid','plan_id','ads_limit','type','expire_date','ads_count','feature_plan_id','payment_method','payment_id','payment','order_id','refund_orderi_d','refund_order_status','available_amt'];
}
