<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Broadcast extends Model
{
    protected $table ="broadcast";
    protected $fillable = ['message','status','date','created_by'];
}
