<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>详情页面</title>

    <link href="//cdn.bootcss.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="//cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</head>

    <div id="title" style="text-align: center;">
        <h1>详情页面</h1>
    </div>
    <hr>
    <div class="container">
        <div class="row">          
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">详情</div>
                    <div class="panel-body">
                        <h4>标题</h4>
                        <p>{{$post['title']}}</p> 
                        <br>   
                        <h4>内容</h4>
                        <p>{{$post['content']}}</p> 
                        <a href="{{ url('post', [$post['post_id'],'edit'])}}"><button class="btn btn-lg btn-info">修改</button></a>
                        <a href="{{ url('post')}}"><button class="btn btn-lg btn-info">列表</button></a>
                        <br>
                        <br>
                        <br>
                        <br>

                        <form action="{{ url('post/' . $post['post_id']) }}" method="POST">
                            {!! csrf_field() !!}            
                            {{ method_field('DELETE') }}
                            <button class="btn btn-lg btn-info">删除</button>
                        </form>    
                    </div>         
                </div>             
            </div>                 
        </div>
    </div>

</body>
</html>
