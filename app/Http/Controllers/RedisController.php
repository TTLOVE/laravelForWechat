<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Article;
use Illuminate\Support\Facades\Redis;

class RedisController extends Controller
{
    public function index()
    {
        $key = 'user:name:6';

        $article = Article::find(6);
        //if($article){
        //    //将用户名存储到Redis中
        //    Redis::set($key,$article->title);
        //}

        //判断指定键是否存在
        if(Redis::exists($key)){
            //根据键名获取键值
            dd(Redis::get($key));
        } else {
            dd('no redis data');
        }
    }

}
