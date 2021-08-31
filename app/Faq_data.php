<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Faq_data extends Model
{
    protected $table = 'faq';
    protected $fillable = ['id','uuid','questions','answers'];
}
