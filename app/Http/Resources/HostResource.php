<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HostResource extends JsonResource
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
            'avatarUrl'=>$this->avatar,
            'signInDate'=> date("Y/m/d",strtotime($this->created_at)),
            'hostId' =>$this->id,
            'hostName'=>$this->name,
            'phoneNumber'=>$this->phone
        ];
    }
}
