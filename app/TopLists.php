<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TopLists extends Model
{
    protected $table = 'toplists_ads';
    protected $fillable = ['ads_id','expire_date','user_id','plan_id'];
}
