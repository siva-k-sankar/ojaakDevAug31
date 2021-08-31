<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Following extends Model
{
	protected $table ="followers";
    protected $fillable = [
        'user_id', 'following','uuid'
    ];
}
