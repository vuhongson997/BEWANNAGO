<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Mail;

class SendWelcomeEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $data;
    public $timeout = 15;
    public $tries = 15;
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
      
        Mail::send('mail.test', [
            'login_url'=>'https://wannago.tk/',
            'username' => $this->data['email'],
            'name'=>$this->data['name']
        ], function ($msg) {
            $msg->to($this->data['email'])->subject('Chào mừng bạn bến với WannaGo!');
        });
        
        
    }
}
