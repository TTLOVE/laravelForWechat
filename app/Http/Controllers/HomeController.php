<?php

namespace App\Http\Controllers;

use Mail;
use App\Http\Requests;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    //public function __construct()
    //{
        //$this->middleware('auth');
    //}

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home')->withArticles(\App\Article::all());
        //return view('home');
    }

    /**
        * 发送邮件
        *
        * @return 
     */
    public function sendMailForQQ()
    {
        $name = '小朱';
        // 发送qq邮件
        $flag = Mail::raw('我的邮件内容哦～', function($message) {
            $message->from('yanzongnet@163.com', '小朱哦');
            $message->subject('邮件主题');
            $message->to('348977791@qq.com');
        });
        if($flag){
            echo '发送邮件成功，请查收！';
        }else{
            echo '发送邮件失败，请重试！';
        }
    }

    public function sendMailFor163()
    {
        $name = '朱雁宗';
        // 发送163邮件
        $flag = Mail::send('emails.testMail',['name'=>$name],function($message){
            $message->from('yanzongnet@163.com', '小朱');
            $to = 'yanzongnet@163.com';
            $message->to($to)->subject('这是我的邮件');
        });
        if($flag){
            echo '发送邮件成功，请查收！';
        }else{
            echo '发送邮件失败，请重试！';
        }
    }
}
