<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer_manage_user extends Model
{
    protected $table = 'customer_manage_user';
    protected $fillable = ['user_id','block_user_id'];
}
