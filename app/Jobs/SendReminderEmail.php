<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Mail;
class SendReminderEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $timeout = 15;
    public $tries = 15;
    public $data;
   
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $a=Mail::send('mail.invoid', [
            'product_name'=>'Wannago',
            'name'=>$this->data['name'],
            'total'=>$this->data['total'],
            'date_pay'=>$this->data['date_pay'],
            'invoice_id'=>$this->data['invoice_id'],
            
            'date'=>$this->data['date'],
            'name_stay'=>$this->data['name_stay'],
            'check_in'=>$this->data['check_in'],
            'check_out'=>$this->data['check_out'],
            'guest_count'=>$this->data['guest_count'],
            'support'=>'sonvhps08306@fpt.edu.vn',
            'develop'=>'TMC'
        ], function ($msg) {
            $msg->to($this->data['email'])->subject('Đơn hàng giao dịch tại WannaGo1!');
        });
        
    }
   
}
