<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $casts = [
        'name'=>'array',
        'name_single'=>'array'
    ];

    public function colors(){
        return $this->belongsToMany('App\Color', 'category_color');
    }

    public function brands(){
        return $this->belongsToMany('App\Brand', 'category_brand');
    }

    public function section(){
        return $this->belongsTo('App\Section', 'parent_id');
    }

    public function items(){
        return $this->hasMany('App\Item');
    }
}
