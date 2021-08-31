<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdsimageTemp extends Model
{
	protected $table ="ads_image_temp";
    protected $fillable = ['ads_id','image','uuid'];
}
