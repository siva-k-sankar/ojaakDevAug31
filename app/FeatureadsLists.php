<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FeatureadsLists extends Model
{
    protected $table = 'featureads_list';
    protected $fillable = ['ads_id','user_id','plan_id'];
}
