<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

class SendRemindEmail extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // 发送163邮件
        Mail::send('emails.testMail',['name'=>'我的名字'],function($message){
            $message->from('yanzongnet@163.com', 'zyz');
            //$to = '348977791@qq.com';
            $to = 'yanzongnet@163.com';
            $message->to($to)->subject('my test for job to send email');
        });
    }
}
