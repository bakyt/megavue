<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name'=>$this->name,
            'avatar'=>$this->avatar,
            'phone_code'=>$this->phone_code,
            'phone'=>$this->phone,
            'birth_date'=>$this->birth_date,
            'gender'=>$this->gender,
            'role'=>$this->roles->first()
            ];
    }
}
