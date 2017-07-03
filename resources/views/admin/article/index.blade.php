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

                    @foreach ($articles as $article)
                        <hr>
                        <div class="article">
                            @foreach ($article['content']['news_item'] as $item)
                                <h4>
                                    {{ $item['title'] }}
                                </h4>
                            @endforeach
                        </div>
                        <div class="article">
                            <p>最后更新时间:{{date("Y-m-d H:i:s",$article['update_time'])}}</p>
                        </div>
                        <form action="{{ url('admin/article/send') }}" method="POST" style="display: inline;">
                            {{ method_field('POST') }}
                            {{ csrf_field() }}
                            <select name="group_id[]" class="selectpicker" data-style="btn-warning" multiple>
                                @foreach ($user_group as $user)
                                    <option value="{{ $user['id'] }}">{{ $user['name'] }}</option>
                                @endforeach
                            </select>
                            
                            <input type="hidden" name="media_id" value="{{$article['media_id']}}">
                            <button type="submit" class="btn btn-lg btn-info">发送</button>
                        </form>
                    @endforeach

                    <br>
                    <div style="margin-top: 40px;">
                        @if ($page_pre>0)
                        <form action="{{ url('admin/article/pageGet') }}" method="POST" style="display: inline;">
                            {{ method_field('POST') }}
                            {{ csrf_field() }}
                            <input type="hidden" name="page" value="{{$page_pre}}">
                            <button type="submit" class="btn btn-lg btn-info">上一页</button>
                        </form>
                        @endif
                        <form action="{{ url('admin/article/pageGet') }}" method="POST" style="display: inline;">
                            {{ method_field('POST') }}
                            {{ csrf_field() }}
                            <input type="hidden" name="page" value="{{$page_next}}">
                            <button type="submit" class="btn btn-lg btn-info">下一页</button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
   </div>
</div>
@endsection
