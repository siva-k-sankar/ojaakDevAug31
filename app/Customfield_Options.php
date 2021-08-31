<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customfield_Options extends Model
{
    protected $table = 'fields_options';
    protected $fillable = ['uuid','field_id','value','created_by'];
}
