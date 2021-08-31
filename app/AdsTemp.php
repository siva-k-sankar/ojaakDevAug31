<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdsTemp extends Model
{	protected $table ="ads_temp";
    protected $fillable = ['uuid','ads_ep_id','categories','sub_categories','title','cities','price','description','tags','seller_id','seller_information','status','approve_status','approved_by','reason','longitude','latitude','area_id','phone_no','plan_id','point','type','sell-name','point_expire_date','brand_id','model_id','purchase_id','pincode'];
}
