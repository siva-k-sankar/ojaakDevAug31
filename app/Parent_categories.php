<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Parent_categories extends Model
{
    protected $table = 'parent_categories';
    protected $fillable = ['name','slug','description','icon','image','status','uuid'];
}
