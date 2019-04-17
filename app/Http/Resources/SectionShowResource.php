<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class SectionShowResource extends JsonResource
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
            'name' => $this->name[app()->getLocale()]?:$this->name['ru'],
            'image' => url(Storage::url($this->image?$this->image:'default-images/category/no-image.jpeg')),
            'children' => SectionResource::collection($this->children),
            'categories' => CategoryResource::collection($this->categories)
        ];
    }
}
