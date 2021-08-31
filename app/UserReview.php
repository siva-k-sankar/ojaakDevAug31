<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserReview extends Model
{
    protected $table = 'user_reviews';
    protected $fillable = ['user_id','review_from_user_id','review'];
}
