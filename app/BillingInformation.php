<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BillingInformation extends Model
{
    protected $table ="billing_information";
    protected $fillable = ['user_id','businessname','gstquestion','gst','addr1','addr2','state','city','username','email',];
}
