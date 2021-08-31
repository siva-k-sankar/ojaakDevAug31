<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customfield extends Model
{
    protected $table = 'fields';
    protected $fillable = ['uuid','name','type','max','default','required','active','created_by','subcategory',];
}
