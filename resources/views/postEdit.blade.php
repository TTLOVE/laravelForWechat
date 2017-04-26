<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>修改页面</title>

    <link href="//cdn.bootcss.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="//cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</head>

    <div id="title" style="text-align: center;">
        <h1>修改页面</h1>
    </div>
    <hr>
    <div class="container">
        <div class="row">          
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">修改</div>
                    <div class="panel-body">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                            <strong>修改失败</strong> 输入不符合要求<br><br>
                            {!! implode('<br>', $errors->all()) !!}
                            </div>            
                        @endif
              
                        <form action="{{ url('post/' . $post['post_id']) }}" method="POST">
                            {!! csrf_field() !!}            
                            {{ method_field('PUT') }}
                            <input type="text" name="title" class="form-control" required="required" placeholder="请输入标题" value="{{$post['title']}}">
                            <br>   
                            <textarea name="body" rows="10" class="form-control" required="required" placeholder="请输入内容">{{$post['content']}}</textarea>
                            <br>   
                            <button class="btn btn-lg btn-info">修改</button>
                        </form>    
          
                    </div>         
                </div>             
            </div>                 
        </div>
    </div>

</body>
</html>
