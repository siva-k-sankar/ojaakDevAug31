<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReportUsers extends Model
{
    protected $table = 'report_users';
    protected $fillable = ['user_id','report_user_id','reason','comments'];
}
