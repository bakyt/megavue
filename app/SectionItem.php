<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SectionItem extends Model
{
    public function section(){
        return $this->belongsTo('App\Section', 'section_id');
    }
    protected $hidden = ['created_at', 'updated_at', 'section_id'];
}
