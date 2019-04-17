<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CategoryShowResource extends JsonResource
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
            'colors' => ColorResource::collection($this->colors),
            'brands' => BrandResource::collection($this->brands),
            'max_price' => $this->max_price,
            'section' => [
                'id' => $this->section->id,
                'name' => $this->section->name[app()->getLocale()]?:$this->section->name['ru']
            ]
        ];
    }
}
