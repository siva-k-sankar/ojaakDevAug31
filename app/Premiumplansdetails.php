<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Premiumplansdetails extends Model
{
    protected $table = 'premiumplansdetails';
   	protected $fillable = ['plan_id','quantity','price','validity','discounts'];
}
