<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $casts = [
        'name'=> 'array'
    ];
    public $timestamps = false;
    public function asJson($value)
    {
        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }
}
