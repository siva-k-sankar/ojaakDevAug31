<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostValuesTemp extends Model
{
    protected $table = 'post_values_temp';
    protected $fillable = [
        'post_id',
        'uuid',
        'field_id',
        'option_id',
        'value',
    ];
}
