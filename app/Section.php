<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Spatie\Permission\Models\Permission;

class Section extends Model
{
    protected $hidden = [
        'updated_at',
        'created_at'
    ];

    /**
     * @param Collection $permissions
     * @param string $type
     * @return Collection
     */
    public static function getByUserPermissions($permissions, $type='menu'){
        $sections = self::all();
        $permissionsArray = $permissions->pluck('name')->toArray();
        $i=0;
        if($type=='menu') foreach ($sections as $section){
            $tempItems = $section->permissionItems($permissionsArray);
            if($tempItems->first()) $sections[$i]->items = $tempItems->where('hidden', '!=', 1);
            else $sections->forget($i);
            $i++;

        }
        elseif ($type=='role') foreach ($sections as $section){
            $tempItems = $section->items()->getResults();
            $j=0;
            foreach ($tempItems as $tempItem) {
                if(in_array($tempItem->route, $permissionsArray)) $tempItems[$j]->access=true;
                else $tempItems[$j]->access=false;
                $j++;
            }
            $sections[$i]->items = $tempItems;
            $i++;

        }
        return $sections;
    }

    /**
     * @param array $permissions_array
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function permissionItems($permissions_array){
        return $this->hasMany('App\SectionItem', 'section_id', 'id')->whereIn('route', $permissions_array)->getResults();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items(){
        return $this->hasMany('App\SectionItem', 'section_id', 'id');
    }
}
