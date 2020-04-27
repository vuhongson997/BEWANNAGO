<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\MPlace as Place;
use App\TStay as Stay;

class HighlineResource extends JsonResource
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
            'imgUrl'=>($this->img_url!=null)?$this->img_url:asset('').'storage/default/default.jpg',
            'cityCode'=>(int)$this->code_place,
            'cityName'=>$this->when($this->code_place,function(){
              
                    $name = Place::where('code_place',$this->code_place)->first();
                   
                return $name['name_place'] ;
            }),
            'averagePrice'=>$this->when($this->code_place,function(){
                $price = Stay::where('city_id',$this->code_place)->avg('price');
                return (int)$price;
            }),
            'totalStays'=>$this->when($this->code_place,function(){
                $count = Stay::where('city_id',$this->code_place)->count();
                return $count;
            }),

        ];
    }
}
