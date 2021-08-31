<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EnquiryMessage extends Model
{
    //
     protected $table = 'enquiry_message';
     protected $fillable = [
          'tickectid',
          'message',
          'type',
     ];

}
