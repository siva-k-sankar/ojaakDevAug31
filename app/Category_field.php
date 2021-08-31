<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category_field extends Model
{
    protected $table ="category_field";
    protected $fillable = ['category_id','uuid','field_id','created_by'];
}
