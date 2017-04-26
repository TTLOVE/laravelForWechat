<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>列表页面</title>

    <link href="//cdn.bootcss.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="//cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
    <script src="//cdn.bootcss.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</head>

    <div id="title" style="text-align: center;">
        <h1>列表页面</h1>
    </div>
    <hr>
    <p style="text-align:center;"><a href="{{ url('post/create')}}"><button class="btn btn-lg btn-info">发布</button></a></p>
    <div id="content">
        <ul>
            @foreach ($posts as $post)
            <li style="margin: 50px 0;">
                <div class="title">
                    <a href="{{ url('post/'.$post['post_id']) }}">
                        <h4>{{ $post['title'] }}</h4>
                    </a>
                </div>
                <div class="body">
                    <p>{{ $post['content'] }}</p>
                </div>
            </li>
            @endforeach
        </ul>
    </div>

</body>
</html>
