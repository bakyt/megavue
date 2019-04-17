<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $casts = [
        'name'=>'array'
    ];
    public function children(){
        return $this->hasMany('App\Section', 'parent_id')->where('active', 1);
    }

    public function categories(){
        return $this->hasMany('App\Category', 'parent_id')->where('status', 2);
    }
}
