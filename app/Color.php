<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    protected $casts = [
        'name' => 'array'
    ];
    public function products(){
        return $this->belongsToMany('App\Products', 'product_color');
    }
}
