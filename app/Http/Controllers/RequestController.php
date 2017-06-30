<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Http\Response;
use Storage;
use App\Jobs\SendRemindEmail;

class RequestController extends Controller
{
    public function getTest(Request $request)
    {
        $getName = $request->input('name', 'klklkl');
        echo $getName;
    }

    public function getUrl(Request $request)
    {
        //匹配request/*的URL才能继续访问
        if(!$request->is('request/*')){
            abort(404);
        }
        $uri = $request->path();
        $url = $request->url();
        $fullUrl = $request->fullUrl();
        echo $uri;
        echo '<br>';
        echo $url;
        echo '<br>';
        echo $fullUrl;
    }

    public function getInputData(Request $request){
        //获取GET方式传递的name参数，默认为LaravelAcademy
        //if ( $request->has('name') ) {
            //$name = $request->input('name');
            //echo $name;
            //echo '<br>';
        //}
        //$nameArr = $request->input('test.*.name');
        //echo "\n\n";
        //echo json_encode($nameArr);
        //echo "\n\n";
        //exit;

        $allData = $request->all();
        echo "<br><br>";
        echo json_encode($allData);
        echo "<br><br>";

        $onlyName = $request->only('name', "test.1");
        echo "<br><br>";
        echo json_encode($onlyName);
        echo "<br><br>";

        $exceptTest = $request->except('test.1');
        echo "<br><br>";
        echo json_encode($exceptTest);
        echo "<br><br>";

        // 缓存当前请求
        $request->flash();

        return redirect('request/last')->withInput();
    }

    public function getLast(Request $request)
    {
        $lastData = $request->old();
        echo "<br><br>";
        echo json_encode($lastData);
        echo "<br><br>";
    }

    public function getCookie(Request $request)
    {
        $cookie = $request->cookie();
        echo json_encode($cookie);
    }

    public function getAddCookie()
    {
        $response = new Response();
        //第一个参数是cookie名，第二个参数是cookie值，第三个参数是有效期（分钟）
        $response->withCookie(cookie('website','ll.com',1));
        //如果想要cookie长期有效使用如下方法
        //$response->withCookie(cookie()->forever('name', 'value'));
        return $response;
    }

    public function getFileUpload()
    {
        return view('fileUpload');
    }

    public function postUploadFile(Request $request)
    {
        //判断请求中是否包含name=file的上传文件
        if(!$request->hasFile('myFile')){
            exit('上传文件为空！');
        }
        $file = $request->file('myFile');
        //判断文件上传过程中是否出错
        if(!$file->isValid()){
            exit('文件上传出错！');
        }

        $filename = time()  . '.' . $file->getClientOriginalExtension();
        $savePath = 'test/' . $filename ;
        $bytes = Storage::put(
            $savePath,
            file_get_contents($file->getRealPath())
        );
        if(!Storage::exists($savePath)){
            exit('保存文件失败！');
        }
        header("Content-Type: ".Storage::mimeType($savePath));
        echo Storage::get($savePath);

        //$destPath = '/tmp/aa';
        //if(!file_exists($destPath))
            //mkdir($destPath,0755,true);
        //$filename = time()  . '.' . $file->getClientOriginalExtension();
        //if(!$file->move($destPath,$filename)){
            //exit('保存文件失败！');
        //}
        //header('Content-type: image/jpg');
        //echo  file_get_contents($destPath . '/' . $filename);;
    }

    public function getJson()
    {
        return response()->json(['name'=>'LaravelAcademy','passwd'=>'LaravelAcademy.org'])->setCallback(request()->input('callback'));
    }

    public function getDownloadByUrl(Request $request)
    {
        $path = $request->input('path', '1493273794.png');
        return response()->download('/tmp/aa/' . $path, '我的.png'); 
    }

    public function getJobSendEmail()
    {
        $job = (new SendRemindEmail())->delay(10);
        $this->dispatch($job);
    }

    public function getSession()
    {
        //session(['site'=>'LaravelAcademy.org']);
        session(['site.xxx.aa'=>'LaravelAcademy.org']);
        $site = session('site.xxx');
        dd($site);
    }

    public function getRequestSession(Request $request)
    {
        $request->session()->put('site.xx', 'll.com');
        if ( $request->session()->has('site.xx') ) {
            $site = $request->session()->get('site');
            dd($site);
        } else {
            $empty = [
                'null' => 111
            ];
            dd($empty);
        }
    }
}
