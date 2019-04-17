<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreType extends Model
{
    protected $casts = [
        'name' => 'array'
    ];
}
