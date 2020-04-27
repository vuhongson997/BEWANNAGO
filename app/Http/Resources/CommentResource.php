<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\TGuest as User;
class CommentResource extends JsonResource
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
            "commentId"=> $this->rating_id,
            "comment"=> $this->comment,
            "userId"=> $this->guest_id,
            "commentRate"=> $this->rate,
            "userName"=> $this->when($this->guest_id,function(){
                $name = User::where('guest_id',$this->guest_id)->first();
                return $name['name'];
            }),
            "userAvatar"=> $this->when($this->guest_id,function(){
                $img = User::where('guest_id',$this->guest_id)->first();
                return $img['avatar'];
            }),
        ];
    }
}
