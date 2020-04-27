<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\TReservationDetail as Detail;
use App\TStay as Stay;
use App\TOccupiedReservation as Pending;
class listCompleteBooking extends JsonResource
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
            'bookingId'=>$this->reservation_id,
            'userId'=>$this->guest_id,
            'customerName' =>$this->custommer_name,
            'phone'=> $this->phone,
            'email'=> $this->email,
            'stayId'=>$this->when($this->reservation_id,function(){
                $getid = Detail::where('reservation_id',$this->reservation_id)->first();
                return $getid->stay_id;
            }),
            'stayName'=>$this->when($this->reservation_id,function(){
                    $getid = Detail::where('reservation_id',$this->reservation_id)->first();
                    $name = Stay::where('stay_id',$getid->stay_id)->first();
                    return $name->stay_name;
            }),
            'checkIn'=>$this->when($this->reservation_id,function(){
                $getid = Detail::where('reservation_id',$this->reservation_id)->first();
                return $getid->check_in;
            }),
            'checkOut'=>$this->when($this->reservation_id,function(){
                $getid = Detail::where('reservation_id',$this->reservation_id)->first();
                return $getid->check_out;
            }),
            'guestCount'=>$this->guest_count,
            'totalPrice'=>$this->price,
            'stripePaymentId'=>$this->payment_code,
            'stripePaymentClientSecret'=>$this->when($this->reservation_id,function(){
                $getid = Pending::where('reservation_id',$this->reservation_id)->first();
                return $getid->secret_id;
            }),
            'status'=>$this->status


        ];
    }
}
