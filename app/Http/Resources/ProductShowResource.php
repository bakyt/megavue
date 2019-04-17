<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProductShowResource extends JsonResource
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
            'images' => $this->images?array_map(function ($image){
                return url(Storage::url($image));
            }, $this->images):[url(Storage::url('default-images/product/no-image.jpeg'))],
            'thumbs' => $this->images?array_map(function ($image){
                return url(Storage::url('thumbs/'.$image));
            }, $this->images):[url(Storage::url('default-images/product/no-image.jpeg'))],
            'price' => $this->price,
            'sale'  => ($this->sale_expires_in && $this->sale)?$this->sale:($this->store->sale_expires_in?($this->store->sale_categories?(in_array($this->category_id, $this->store->sale_categories)?$this->store->sale:0):$this->store->sale):0),
            'colors' => ColorResource::collection($this->colors),
            'category' => new CategoryResource($this->category),
            'additional_info' => $this->additional_info[app()->getLocale()]?:$this->additional_info['ru'],
            'store' => new StoreResource($this->store),
            'body' => $this->body?($this->body[app()->getLocale()]?:$this->body['ru']):[],
            'country' => new CountryResource($this->country)
        ];
    }
}
