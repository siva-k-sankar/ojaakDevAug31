<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlansLimit extends Model
{
    protected $table = 'plans_limit';
    protected $fillable = ['plan_id','uuid','user_id','adslimit'];
}
