<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\MCode as Type;
use App\TAddress as Address;
use App\TRoomGallery as Album;
use App\TStayRating as Rate;
use App\M_province as Province;
use App\M_district as District;
use App\M_ward as Ward;
use App\Http\Resources\stayUtility;
class DetailResource extends JsonResource
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
            'hostId'        => $this->host_id,
            'stayTypeName'  => $this->when(Type::where('code_group',1)->where('lang',(isset($this->lang))?$this->lang:"VI")->first(),function(){
                $type = Type::where('code_group',1)->where('lang',(isset($this->lang))?$this->lang:"VI")->first();
                return $type->code_name;
            }),
            'stayId'        => $this->stay_id,
            'lng'           => $this->when(Address::where('stay_id',$this->stay_id)->first(),function(){
                            $lng = Address::where('stay_id',$this->stay_id)->first();
                            return $lng->lng;
            }),
            'lat'           => $this->when(Address::where('stay_id',$this->stay_id)->first(),function(){
                            $lat = Address::where('stay_id',$this->stay_id)->first();
                            return $lat->lat;
            }),
            'stayName'      => $this->stay_name,
            'stayDescription'=>$this->description,
            'areaSquare'    => $this->when(Address::where('stay_id',$this->stay_id)->first(),function(){
                $area = Address::where('stay_id',$this->stay_id)->first();
                return $area->area;
            }),
            'rate'          => $this->when(Rate::where('stay_id',$this->stay_id)->first(),function(){
                $rate = Rate::where('stay_id',$this->stay_id)->avg('rate');
                return round($rate,1);
            }), 
            'ratingCount'   => $this->when(Rate::where('stay_id',$this->stay_id)->first(),function(){
                $count = Rate::where('stay_id',$this->stay_id)->count();
                return $count;
            }),
            'guestCount' => $this->guest_count,
            'bedCount'   => $this->bed_count,
            'bathCount'  =>$this->bath_count,
            'price'      =>($this->discount)?$this->discount:$this->price,
            'discount'  => ($this->discount)?$this->price:$this->discount,
            'city'      => $this->when(Province::where('province_id',$this->city_id)->first(),function(){
                $city = Province::where('province_id',$this->city_id)->first();
                return $city->name;
            }),
            'district' => $this->when(Address::where('address_id',$this->address_id)->first(),function(){
                $area = Address::where('address_id',$this->address_id)->first();
                if($area['district_id']){
                $dis = District::where('district_id',$area['district_id'])->first();
                return $dis->name;
                } else return null;
            }),
            'ward' => $this->when(Address::where('address_id',$this->address_id)->first(),function(){
                $area = Address::where('address_id',$this->address_id)->first();
                if ($area['ward_id']){
                $ward = Ward::where('ward_id',$area['ward_id'])->first();
                return $ward->name;
                }else return null;
            }),
            'street' => $this->when(Address::where('address_id',$this->address_id)->first(),function(){
                $area = Address::where('address_id',$this->address_id)->first();
                return $area->street;
            }),
            'addressNumber' => $this->when(Address::where('address_id',$this->address_id)->first(),function(){
                $area = Address::where('address_id',$this->address_id)->first();
                return $area->address_number;
            }),
            'stayUtility'=>[
                'wifi' => $this->wifi==1?true:false,
                'tivi' => $this->smoking==1?true:false,
                'kitchen'=>$this->kitchen==1?true:false,
                'pool'=>$this->pool==1?true:false,
                'refrigerator'=>$this->refrigerator==1?true:false,
                'cooler' =>$this->cooler==1?true:false
            ],
            'imgUrls' => $this->when(Album::where('stay_id',$this->stay_id)->first(),function(){
                $imgs = Album::where('stay_id',$this->stay_id)->get();
                foreach($imgs as $img){
                    return json_decode($img->img_url);
                }
                
            }),



        ];
    }
}
