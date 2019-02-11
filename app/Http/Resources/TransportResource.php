<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransportResource extends JsonResource
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
            'id'=>$this->id,
            'type'=>$this->type->name,
            'user_id'=>$this->user_id,
            'user_name'=>$this->user->name,
            'user_phone'=>$this->user->phone,
            'brand'=>$this->brand->name,
            'model'=>$this->model->name,
            'color'=>$this->color->name,
            'number'=>$this->number,
            'rating'=>$this->rating,
            'image'=>$this->image
        ];
    }
}
