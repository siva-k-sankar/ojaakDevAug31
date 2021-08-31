<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Makeanoffers extends Model
{	
	protected $table ="make_an_offers";

    protected $fillable = ['uuid','user_id','adsid','amount'];
}
