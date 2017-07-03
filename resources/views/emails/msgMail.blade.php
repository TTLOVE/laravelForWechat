<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">群发消息状态邮件</div>
                <div class="panel-body">
                    <hr>
                    @if ($response['errcode']==0)
                        <h1>发送成功</h1> 
                    @else
                        <h1 style="color:red;">发送失败</h1> 
                    @endif
                    
                    <div class="article">     
                        <h4>发送分组id：{{$postData['filter']['group_id']}}</h4>
                    </div>                    
                    <div class="article">
                        <h4>消息media　id：{{$postData['mpnews']['media_id']}}</h4>
                    </div>             
                    @if ($response['errcode']==0)
                        <div class="article">     
                            <h4>返回数据-消息id：{{$response['msg_id']}}</h4>
                        </div>            
                        <div class="article">
                            <h4>返回数据-消息数据id：{{$response['msg_data_id']}}</h4>
                        </div>           
                    @else
                        <div class="article">     
                            <h4>返回数据-消息id：{{$response['msg_id']}}</h4>
                        </div>            
                        <div class="article">     
                            <h4>返回数据-错误码：{{$response['errcode']}}</h4>
                        </div>            
                        <div class="article">
                            <h4>返回数据-消息数据id：{{$response['errmsg']}}</h4>
                        </div>           
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
