<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Privacy extends Model
{
    protected $table = 'privacy';
    protected $fillable = ['user_id','phone','mail','online','view_chat'];
}
