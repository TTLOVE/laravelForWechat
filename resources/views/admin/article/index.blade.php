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
                                <h4>{{ $item['title'] }}</h4>
                            @endforeach
                        </div>
                        <form action="{{ url('admin/article/send') }}" method="POST" style="display: inline;">
                            {{ method_field('POST') }}
                            {{ csrf_field() }}
                            <select name="group_id" class="selectpicker" data-style="btn-warning">
                                @foreach ($user_group as $user)
                                    <option value="{{ $user['id'] }}">{{ $user['name'] }}</option>
                                @endforeach
                            </select>
                            
                            <input type="hidden" name="media_id" value="{{$article['media_id']}}">
                            <button type="submit" class="btn btn-lg btn-info">发送</button>
                        </form>
                    @endforeach

                </div>
            </div>
        </div>
   </div>
</div>
@endsection
