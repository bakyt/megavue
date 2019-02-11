<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'from_geo'=>$this->from_geo,
            'to_geo'=>$this->to_geo,
            'client'=>$this->client,
            'transport'=>new TransportResource($this->transport),
            'region'=>$this->region,
            'price'=>$this->price,
            'transport_type'=>$this->transportType
        ];
    }
}
