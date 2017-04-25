@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">微信文章管理</div>
                <div class="panel-body">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            {!! implode('<br>', $errors->all()) !!}
                        </div>
                    @endif

                    <hr>
                    <h1>发送成功</h1>
                    <a href="{{ url('admin/article') }}"><button type="submit" class="btn btn-lg btn-info">返回列表</button></a>
                    <div class="article">
                        <h4>发送分组id：{{$group_id}}</h4>
                    </div>
                    <div class="article">
                        <h4>消息media　id：{{$media_id}}</h4>
                    </div>
                    <div class="article">
                        <h4>返回数据-消息id：{{$msg_id}}</h4>
                    </div>
                    <div class="article">
                        <h4>返回数据-消息数据id：{{$msg_data_id}}</h4>
                    </div>
                </div>
            </div>
        </div>
   </div>
</div>
@endsection
