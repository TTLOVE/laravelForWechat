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
                    <div class="article">
                        <h4>选择发送分组</h4>
                    </div>
                    <form action="{{ url('admin/article/send') }}" method="POST" style="display: inline;">
                        {{ method_field('POST') }}
                        {{ csrf_field() }}
                        <select name="group_id" class="selectpicker">
                            @foreach ($user_group as $user)
                                <option value="{{ $user['id'] }}">{{ $user['name'] }}</option>
                            @endforeach
                        </select>
                        
                        <input type="hidden" name="media_id" value="{{$media_id}}">
                        <button type="submit" class="btn btn-lg btn-info">发送</button>
                    </form>
                </div>
            </div>
        </div>
   </div>
</div>
@endsection
