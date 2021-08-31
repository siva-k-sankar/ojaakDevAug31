<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sub_categories extends Model
{
    protected $table = 'sub_categories';
    protected $fillable = ['uuid','parent_id','name','slug','description','status','tag'];
}
