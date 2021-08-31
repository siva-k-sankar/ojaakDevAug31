<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'help',
	    'name',
	    'email',
	    'description',
	    'mobileno',
	    'attachments',
    ];
}