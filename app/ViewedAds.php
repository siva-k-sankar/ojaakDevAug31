<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ViewedAds extends Model
{
    //
    protected $table = "user_viewed_ads";
    protected $fillable = [
        'user_id', 'ads_id'
    ];
}
