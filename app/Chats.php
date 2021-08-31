<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chats extends Model
{
    protected $table ="chats";
    protected $fillable = ['unique_chats_id','ads_id','user_1','user_2','user_1_read_status','user_2_read_status'];
}
