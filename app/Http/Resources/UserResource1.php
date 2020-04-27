<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Role;
use App\M_code;
class UserResource1 extends JsonResource
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
                'name' => $this->name,
                'email' => $this->email,
                'role' => $this->when(Role::where('id',$this->role_id)->first(),function(){
                    $d = Role::where('id',$this->role_id)->first();
                    return $d->name;
                }),
                'test' =>$this->when(M_code::where('code',1)->first(),function(){
                    $c = M_code::where('code',1)->first();
                    return $c->code_name;
                }),
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at
           
        ];
    }
}
