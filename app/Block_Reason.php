<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Block_Reason extends Model
{
    protected $table ="block_reasons";
    protected $fillable = ['user_id','ads_id','reason','blocked_by','status'];
}
