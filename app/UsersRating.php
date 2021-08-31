<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsersRating extends Model
{
    protected $table = 'users_ratings';
    protected $fillable = ['user_id','rating_from_user_id','rating'];
}
