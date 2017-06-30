<?php

namespace App\Http\Controllers;

use Mail;
use App\Http\Requests;
use Illuminate\Http\Request;
use DB;
use Log;

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
        $articles = DB::table('articles')->orderBy('id', 'desc')->simplePaginate(3);
        //$articles = DB::table('articles')->where('id', '>', 2)->orderBy('id', 'desc')->paginate(3);
        return view('home')->withArticles($articles);
    }

    /**
        * 测试链接参数验证只有英文
        *
        * @param $name 传输的参数
        *
        * @return 
     */
    public function home($name)
    {
        abort(403,'呵呵哒，您无权访问该页面！');
        echo $name;
    }

    public function log(){
        //Log::emergency("系统挂掉了");
        //Log::alert("数据库访问异常");
        //Log::critical("系统出现未知错误");
        //Log::error("指定变量不存在");
        //Log::warning("该方法已经被废弃");
        //Log::notice("用户在异地登录");
        //Log::info("用户xxx登录成功", ['user_info' => ['id'=>1, 'name'=> 233]]);
        //Log::debug("调试信息");
        $monolog = Log::getMonolog();
        dd($monolog);
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
