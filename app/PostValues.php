<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostValues extends Model
{
    protected $table = 'post_values';
    protected $fillable = [
        'post_id',
        'uuid',
        'field_id',
        'option_id',
        'value',
    ];
}
