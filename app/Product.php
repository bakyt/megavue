<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $casts = [
        'title'             => 'array',
        'body'              => 'array',
        'body_fields'       => 'array',
        'additional_info'   => 'array',
        'images'            => 'array'

    ];
    public function colors(){
        return $this->belongsToMany('App\Color', 'product_color');
    }

    public function store(){
        return $this->belongsTo('App\Store');
    }

    public function brand(){
        return $this->belongsTo('App\Brand');
    }

    public function category(){
        return $this->belongsTo('App\Category');
    }

    public function sale(){
        return ($this->sale_expires_in && $this->sale)?$this->sale:($this->store->sale_expires_in?($this->store->sale_categories?(in_array($this->category_id, $this->store->sale_categories)?$this->store->sale:0):$this->store->sale):0);
    }

    public function country(){
        return $this->belongsTo('App\Country');
    }
}
