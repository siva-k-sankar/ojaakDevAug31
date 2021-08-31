<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatsMessage extends Model
{
    protected $table ="chats_message";
    protected $fillable = ['chat_id','msg','user_id','image','video','location','status','read_status'];
}
