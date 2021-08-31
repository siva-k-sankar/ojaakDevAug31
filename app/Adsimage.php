<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Adsimage extends Model
{
	protected $table ="ads_image";
    protected $fillable = ['ads_id','image','uuid'];
}
