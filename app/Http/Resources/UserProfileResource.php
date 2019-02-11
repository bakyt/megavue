<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
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
            'pin'=>$this->pin,
            'name'=>$this->name,
            'sname'=>$this->sname,
            'fname'=>$this->fname,
            'username'=>$this->username,
            'email'=>$this->email,
            'post'=>$this->post,
            'readonly'=>$this->readonly,
            'active'=>$this->active,
            'role'=>$this->role,
            'position'=>$this->position,
            'lastSeen'=>$this->lastSeen
        ];
    }
}
