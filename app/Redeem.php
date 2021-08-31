<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Redeem extends Model
{
    protected $table = 'redeem';
    protected $fillable = [
        'user_id',
        'redeem_amt', 
        'mid',
       'orderId',
       'paytmOrderId',
       'commissionAmount',
       'tax',
    ];
}
