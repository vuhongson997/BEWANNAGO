<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\TReservation as Order;
use App\TReservationDetail as Detail;
use App\TOccupiedReservation as Pending;
use App\Http\Resources\bookingResource;
use App\Http\Resources\listInCompleteBooking;
use App\Http\Resources\listCompleteBooking;
use App\TStay as Stay;
use App\Jobs\SendReminderEmail;
use App\FailedJob;
class BookingController extends Controller
{
    public function getbooking($id){
        $data = Order::where('reservation_id',$id)->first();
        $name_stay = Stay::where('stay_id',$data->reservation_detail->stay_id)->first();
        return response()->json([
            'bookingId'=>$data->reservation_id,
            'userId'=>$data->guest_id,
            'customerName' =>$data->custommer_name,
            'phone'=> $data->phone,
            'email'=> $data->email,
            'stayId'=>$data->reservation_detail->stay_id,
           
            'stayName'=>$name_stay->stay_name,
            'checkIn'=>$data->reservation_detail->check_in,
           
            'checkOut'=>$data->reservation_detail->check_out,
           
            'guestCount'=>$data->guest_count,
            'totalPrice'=>$data->price,
            'stripePaymentId'=>$data->payment_code,
            'stripePaymentClientSecret'=>($data->tripe->secret_id)?$data->tripe->secret_id:'',
            
            'status'=>$data->status

        ],200);
        
    }

    public function addBooking(Request $request){
        $check=Pending::where('guest_id',$request->userId)->where('reservation_id', $request->bookingId)->orWhere('stay_id',$request->stayId)
        ->where('check_out',date("Y-m-d", strtotime($request->checkOut)))->where('check_in',date("Y-m-d", strtotime($request->checkIn)))
        ->where('status','pending')->first();
        if($check){
            $name_stay = Stay::where('stay_id',$check->stay_id)->first();   
        return response()->json([
            'bookingId'=>$check->reservation_id,
            'userId'=>$check->guest_id,
            'customerName' =>$check->cus_name,
            'phone'=> $check->phone,
            'email'=> $check->email,
            'stayId'=>$check->stay_id,
           
            'stayName'=>$name_stay->stay_name,
            'checkIn'=>$check->check_in,
           
            'checkOut'=>$check->check_out,
           
            'guestCount'=>$check->guest_count,
            'totalPrice'=>$check->price,
            'stripePaymentId'=>$check->payment_id,
            'stripePaymentClientSecret'=>$check->secret_id,
            'status'=>'payment waiting'
        ],200);
        }else{
        $order = Order::create([
            'guest_id' => $request->userId,
            'custommer_name'=>$request->customerName,
            'phone'=>$request->phone,
            'email'=>$request->email,
            'price'=>$request->totalPrice,
            'payment_code'=>$request->stripePaymentId,
            'status'=>'pending',
            'guest_count'=>$request->guestCount
        ]);
            $reservation_id=$order->reservation_id;
        Detail::create([
            'check_out'=>date("Y-m-d", strtotime($request->checkOut)),
            'check_in'=>date("Y-m-d", strtotime($request->checkIn)),
            'quantity'=>1,
            'reservation_id'=>$reservation_id,
            'stay_id'=>$request->stayId
            ]);
        Pending::create([
            'reservation_id'=>$reservation_id,
            'check_out'=>date("Y-m-d", strtotime($request->checkOut)),
            'check_in'=>date("Y-m-d", strtotime($request->checkIn)),
            'status'=>'pending',
            'stay_id'=>$request->stayId,
            'guest_id' => $request->userId,
            'cus_name'=>$request->customerName,
            'phone'=>$request->phone,
            'email'=>$request->email,
            'guest_count'=>$request->guestCount,
            'price'=>$request->totalPrice,
        ]);    
        $name_stay = Stay::where('stay_id',$request->stayId)->first();   
            return response()->json([
                'bookingId'=>$order->reservation_id,
                'userId'=>$request->userId,
                'customerName' =>$request->customerName,
                'phone'=> $request->phone,
                'email'=> $request->email,
                'stayId'=>$request->stayId,
                'stayName'=>$name_stay->stay_name,
                'checkIn'=>$request->checkIn,
                'checkOut'=>$request->checkOut,
                'guestCount'=>$request->guestCount,
                'totalPrice'=>$request->totalPrice,
                'stripePaymentId'=>$request->stripePaymentId,
                'stripePaymentClientSecret'=>$request->stripePaymentClientSecret,
                'status'=>'payment waiting'
            ], 200);
        }
    }

    public function updateBooking(Request $request){
        //    return $request ->all(); exit;

        $order = Order::updateOrCreate(
            ['reservation_id'=>$request->bookingId],
            [
            'guest_id' => $request->userId,
            'custommer_name'=>$request->customerName,
            'phone'=>$request->phone,
            'email'=>$request->email,
            'price'=>$request->totalPrice,
            'status'=>$request->status,
            'guest_count'=>$request->guestCount,

        ]);

        Detail::updateOrCreate(
            ['reservation_id'=>$request->bookingId],
            [
            'check_out'=>date("Y-m-d", strtotime($request->checkOut)),
            'check_in'=>date("Y-m-d", strtotime($request->checkIn)),
            'quantity'=>1,
            'stay_id'=>$request->stayId,

            ]);

            $a = Pending::updateOrCreate(
                ['guest_id'=>$request->userId,'stay_id'=>$request->stayId],
                [
                'check_in'=>date("Y-m-d", strtotime($request->checkIn)),
                'check_out'=>date("Y-m-d", strtotime($request->checkOut)),
                'status'=>$request->status,
                'stay_id'=>$request->stayId,
                'cus_name'=>$request->customerName,
                'guest_id'=>$request->userId,
                'phone'=>$request->phone,
                'email'=>$request->email,
                'guest_count'=>$request->guestCount,
                'price'=>$request->totalPrice   
                ]
            );
            return response()->json(['status'=>200,'data'=>$a], 200);
    }

    public function getAllBookedList($id){
                $data = Order::where('guest_id',$id)->get();
                return bookingResource::collection($data);
    }

    public function cancelBookingById($request){
        Order::where('reservation_id',$request)->update([
            'status' => 'cancel'
        ]);
        Pending::where('reservation_id',$request)->delete();
        
        return response()->json(['status'=>1],202);
        
    }

    public function getIncompleteList($id){
        $data=Order::where('guest_id',$id)->where('status','pending')->get();
        return listInCompleteBooking::collection($data);
    }

    public function getCompletedList($id){
        $data=Order::where('guest_id',$id)->where('status','succeeded')->get();
        return listCompleteBooking::collection($data);
    }

    public function sendInvoid(Request $request){
        $reponse=Pending::where('payment_id',$request->payment_id)->where('mail_send',0)->first();  
        // Pending::where('payment_id',$request->id)->where('mail_send',0)->update([
        //     'mail_send'=>1
        // ]);  
        $data=[
            'name'=>$reponse->cus_name,
            'total'=>$reponse->price,
            'date_pay'=>date('d-m-Y H:i',strtotime($reponse->updated_at)),
            'invoice_id'=>$reponse->reservation_id,
           
            'date'=>date('d-m-Y H:i',strtotime($reponse->created_at)),
            'name_stay'=>$reponse->stay->stay_name,
            'check_in'=>date('d-m-Y',strtotime($reponse->check_in)),
            'check_out'=>date('d-m-Y',strtotime($reponse->check_out)),
            'guest_count'=>$reponse->guest_count,
            //'email'=>$reponse->email
            'email'=>'vuhongson97@gmail.com'
        ];
        dispatch(new SendReminderEmail($data));
        return response()->json($data,200);
        
    }
   

    public function get_fail(){
        $data=FailedJob::first();
        
        return $data['payload'];
        
    }
}
