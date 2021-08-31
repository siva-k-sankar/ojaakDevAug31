<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdsReview extends Model
{
    protected $table = 'ads_reviews';
    protected $fillable = ['user_id','review','ads_id'];
}
