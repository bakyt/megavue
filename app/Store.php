<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $casts = [
        'sale_categories' => 'array',
        'description' => 'array',
        'categories' => 'array',
        'contacts' => 'array',
        'about' => 'array',
        'address_map' => 'array'
    ];
    public function users(){
        return $this->belongsToMany('App\User', 'user_store');
    }
    public function storeTypes(){
        return $this->belongsToMany('App\StoreType', 'store_type', 'store_id', 'type_id');
    }
    public function cats(){
        return $this->categories?Category::whereIn('id', $this->categories)->get():[];
    }
    public function products(){
        return $this->hasMany('App\Product');
    }
    public function orders() {
        return $this->hasMany('App\Order');
    }
}
