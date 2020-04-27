<?php

namespace App\Http\Controllers\api;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\tripePayment as Payment;
use App\TReservation as Order;
use App\TOccupiedReservation as Pending;
class StripeController extends BaseController
{

    function paymentIntent(Request $request)
    {
    
        // composer require stripe/stripe-php
        // laravel 5.8.*
        \Stripe\Stripe::setApiKey('sk_test_itoOP6VRXM6FyAJSGu4z1Ri0009OG6RjHd'); // <- không đổi key này, để nó vào constant (ENV)
       
        
        // \Stripe\Stripe::setApiKey('sk_test_n66XQ91p9YUgZ50XVlfCZfTT00wczcy0q9'); // <- không đổi key này, để nó vào constant (ENV)

        /// phải check xem user có tạo payment trước đó không, nếu đã có payment và chưa thanh toán => ngừng xử lý
        $get = Payment::where('userId',$request->userId)->where('bookingId',$request->bookingId)->first();
        
        if(!$get){
        
        ///
        $intent = \Stripe\PaymentIntent::create([
            'amount' => $request->totalPrice,
            'currency' => 'vnd',
            'receipt_email' => $request->email,
            
            // Verify your integration in this guide by including this parameter
            'metadata' => ['integration_check' => 'accept_a_payment'],
        ]);
        Payment::create([
            'id_payment'=>$intent['id'],
            'amount'=>$intent['amount'],
            'client_secret' => $intent['client_secret'],
            'created'=>$intent['created'],
            'currency'=>$intent['currency'],
            'customer' => $intent['customer'],
            'description' => $intent['description'],
            'receipt_email' => $intent['receipt_email'],
            'status' => 'incomplete',
            'bookingId'=>$request->bookingId,
            'userId'=>$request->userId,
            
        ]);
        Pending::where('reservation_id',$request->bookingId)
        ->update([
            'status'=>'incomplete',
            'payment_id'=>$intent['id'],
            'secret_id'=>$intent['client_secret'],
            
        ]);
        Order::where('reservation_id',$request->bookingId)
        ->update([
            'payment_code'=>$intent['id'],
            'status'=>'incomplete'
        ]);    
        return response()->json([
            'id'=>$intent['id'],
            'amount'=>$intent['amount'],
            'clientSecret' => $intent['client_secret'],
            'created'=>$intent['created'],
            'currency'=>$intent['currency'],
            'customer' => $intent['customer'],
            'description' => $intent['description'],
            'receiptEmail' => $intent['receipt_email'],
            'status' => 'incomplete'
            // ...
        ],200);
        }else{
            // trả nguyên cái $intent về
                
            return response()->json([
                'id'=>$get['id_payment'],
                'amount'=>$get['amount'],
                'clientSecret' => $get['client_secret'],
                'created'=>$get['created'],
                'currency'=>$get['currency'],
                'customer' => $get['customer'],
                'description' => $get['description'],
                'receiptEmail' => $get['receipt_email'],
                'status' => $get['status']
                // ...
            ],200);
        }


         
       

    }
    public function confirmPayment(Request $request){
        try 
        {
            $re=Order::where('payment_code',$request->id)->update(
                [
                    'status'=>$request->status
                ]
            );
            Pending::where('payment_id',$request->id)
            ->update([
            'status'=>$request->status
            ]);
            if($re){
                Payment::where('id_payment',$request->id)->delete();
                return response()->json(['status'=>200],200);
            }else{
                return response()->json(['status'=>'confirm-fail'],400);
            }
                    
        
        } catch (Exception $e) {
            response()->json(array('status' => 0, 'message' => $e->getMessage),400);
        }         
    }
    // 
    public function sendInvoid($id){
        $reponse=Pending::where('payment_id',$id)->where('mail_send',0)->first();  
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
            'email'=>$reponse->email
            // 'email'=>'vuhongson97@gmail.com'
        ];
        dispatch(new SendReminderEmail($data));
        return response()->json($data,200);
        
    }
}