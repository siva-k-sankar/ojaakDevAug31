<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model
{
    //
     protected $table = 'enquiry';
     protected $fillable = [
     	'help',
          'tickectid',
     	'name',
     	'mail_id',
     	'ad_id',
     	'description',
     	'mobileno',
     	'attachments',
          'status'
     ];
}
