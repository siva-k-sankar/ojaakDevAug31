<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RejectReason extends Model
{
    protected $table = 'reject_reason';
    protected $fillable = ['ads_id','reason','rejected_by'];
}
