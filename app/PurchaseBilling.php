<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseBilling extends Model
{
    protected $table ="purchase_billing";
    protected $fillable = ['plan_id','businessname','gstquestion','gst','addr1','addr2','state','city','username','email',];
}
