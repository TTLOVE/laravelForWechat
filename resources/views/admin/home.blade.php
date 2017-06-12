@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">微信后台 @if ($user_id == 2)(正式) @else (测试) @endif </div>

                <div class="panel-body">

                    <a href="{{ url('admin/article') }}" class="btn btn-lg btn-success col-xs-12">微信文章列表</a>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
