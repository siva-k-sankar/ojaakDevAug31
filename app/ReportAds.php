<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReportAds extends Model
{
    protected $table = 'report_ads';
    protected $fillable = ['user_id','report_ads_id','reason','comments'];
}
