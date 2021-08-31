<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Plan_history extends Model
{
    protected $table = 'plans_history';
    protected $fillable = ['plan_id','uuid','modified_name','modified_price','modified_limit','modified_admin_id','status','modified_date'];
}
