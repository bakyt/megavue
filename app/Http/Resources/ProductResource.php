<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProductResource extends JsonResource
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
            'title' => $this->title[app()->getLocale()]?:$this->title['ru'],
            'image' => url(Storage::url($this->images?$this->images[0]:'default-images/product/no-image.jpeg')),
            'price' => $this->price,
            'sale'  => ($this->sale_expires_in && $this->sale)?$this->sale:($this->store->sale_expires_in?($this->store->sale_categories?(in_array($this->category_id, $this->store->sale_categories)?$this->store->sale:0):$this->store->sale):0),
            'colors' => ColorResource::collection($this->colors),
            'color' => isset($this->colors[0])?new ColorResource($this->colors[0]):null,
            'store' => new StoreResource($this->store),
            //'country' => new CountryResource($this->country)
        ];
    }
}
