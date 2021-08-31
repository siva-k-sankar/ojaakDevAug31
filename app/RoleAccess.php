<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoleAccess extends Model
{
    protected $table = 'role_accesses';
    protected $fillable = ['role_id','page_id','allow_all','view_all'];
}
