<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name','slug','role_id'];
    
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
