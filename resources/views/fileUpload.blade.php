<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>上传页面</title>

    <link href="//cdn.bootcss.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="//cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</head>

    <div id="title" style="text-align: center;">
        <h1>上传页面</h1>
    </div>
    <hr>
    <div class="container">
        <div class="row">          
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">上传<a href="{{ url('post')}}">返回首页</a></div>

                    <div class="panel-body">
              
                        <form action="{{ url('request/upload-file') }}" method="POST" enctype="multipart/form-data">
                            {!! csrf_field() !!}            
                            <input type="file" name="myFile" class="form-control" >
                            <br>   
                            <button class="btn btn-lg btn-info">上传</button>
                        </form>    
          
                    </div>         
                </div>             
            </div>                 
        </div>
    </div>

</body>
</html>
