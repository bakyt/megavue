<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class StoreShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description[app()->getLocale()]?:$this->description['ru'],
            'background_image' => url(Storage::url($this->background_image?$this->background_image:'default-images/store/background.jpeg')),
            'icon' => url(Storage::url($this->icon?$this->icon:'default-images/store/icon.png')),
            'slug' => $this->slug,
            'categories' => $this->categories?CategoryShowResource::collection($this->cats()):[],
            'contacts' => $this->contacts,
            'about' => isset($this->about[app()->getLocale()])?$this->about[app()->getLocale()]:isset($this->about['ru'])?$this->about['ru']:null,
            'types' => StoreTypeResource::collection($this->storeTypes),
            'address' => $this->address,
            'address_map' => $this->address_map
        ];
    }
}
