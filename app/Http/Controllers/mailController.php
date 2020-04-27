<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\SendEmailJob;
use App\Jobs\SendWelcomeEmail;
use Mail;
class mailController extends Controller

{
    public function index(){
        
        // $emailJob = new SendWelcomeEmail();
        // dispatch($emailJob);
        return view('mail');
        
    }
    public function send(Request $request){
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'body' => 'required',
        ]);
        if($validatedData){
            $details=[
                "email"=>"vuhongson97@gmail.com",
                "title"=>$request->title,
                "body"=>$request->body
            ];
            
            Mail::send('mail.gg', $details, function ($msg) {
                $msg->to('vuhongson97@gmail.com')->subject('Đơn hàng giao dịch tại WannaGo!');
            });
            return back();
        }

    }

}
