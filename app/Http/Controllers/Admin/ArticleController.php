<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Article;
use Ixudra\Curl\Facades\Curl;
use Illuminate\Support\Facades\Auth;
use Mail;

class ArticleController extends Controller
{
    private $channelId = 11;

    public function __construct()
    {
        $userId = Auth::id();
        if ( $userId==3 ) {
            $this->channelId = 1;
        }
    }

    /**
        * 文章列表
        *
     */
    public function index()
    {
        $articleList = $this->getArticleList($this->channelId);
        $userGroupList = $this->getUserGroupList($this->channelId);
        $pageNum = 1;
        $pagePre = $pageNum - 1;
        $pageNext = $pageNum + 1;
        return view('admin/article/index')->withArticles($articleList['item'])->withUserGroup($userGroupList['groups'])->withPagePre($pagePre)->withPageNext($pageNext);
    }

    /**
        * 获取列表
        *
        * @param $request
        *
        * @return  页面
     */
    public function pageGet(Request $request)
    {
        $pageNum = $request->get('page');
        $offset = ($pageNum-1) * 10;
        // 获取列表
        $userGroupList = $this->getUserGroupList($this->channelId);
        $articleList = $this->getArticleList($this->channelId, $offset);

        $pagePre = $pageNum - 1;
        $pageNext = $pageNum + 1;
        return view('admin/article/index')->withArticles($articleList['item'])->withUserGroup($userGroupList['groups'])->withPagePre($pagePre)->withPageNext($pageNext);

    }

    /**
        * 发送消息
        *
        * @param $request 请求信息
        *
        * @return array
     */
    public function send(Request $request)
    {
        $this->validate($request, [
            'media_id' => 'required',
            'group_id' => 'required',
        ]);
        $mediaId = $request->get('media_id');
        $groupId = $request->get('group_id');

        if ( empty($groupId) ) {
            return redirect()->back()->withInput()->withErrors('没有选中分组！');
        }
    
        $response = $this->sendMsg($mediaId, $groupId, $this->channelId);

        if ( isset($response['status']) && $response['status']!=1 ) {
            return redirect()->back()->withInput()->withErrors($response['msg']);
        } else {
            if ( isset($response['errcode']) && $response['errcode']==0 ) {
                return view('admin/article/send')->withMediaId($mediaId)->withGroupId($groupId)->withChannelId($this->channelId)->withMsgId($response['msg_id'])->withMsgDataId($response['msg_data_id']);
            } else {
                return redirect()->back()->withInput()->withErrors($response['errmsg']);
            }
        }
    }

    /**
        * 根据媒体id,群组id,渠道id发送消息
        *
        * @param $mediaId 媒体id
        * @param $groupId 群组id
        * @param $channelId 渠道id
        *
        * @return array
     */
    private function sendMsg($mediaId, $groupId, $channelId)
    {
        $wechatToken = $this->getWechatToken($channelId);
        $returnData = array(
            'status' => 0,
            'msg' => '获取失败'
        );
        if ( empty($wechatToken) ) {
            $returnData['status'] = -1; 
            $returnData['msg'] = "无token，失败！";
            return $returnData;
        }

        // 发送消息
        $sendUrl = 'https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token=' . $wechatToken;
        $postData = [
            "filter" => [
                "is_to_all" => false,
                "group_id" => $groupId
            ],
            "send_ignore_reprint" => 1,
            "mpnews" => [
                "media_id" => $mediaId
            ],
            "msgtype" => "mpnews"
        ];
        $response = Curl::to($sendUrl)
            ->withData($postData)
            ->asJson()
            ->post();

        if ( empty($response) ) {
            $returnData['status'] = -1; 
            $returnData['msg'] = "发送失败！";
            return $returnData;
        }
        $response = json_decode(json_encode($response), true);

        // 发送163邮件
        Mail::send('emails.testMail',['postData'=>$postData, 'response' => $response],function($message){
            $userId = Auth::id();
            $userName = '用户' . $userId . '群发消息';
            $message->from('yanzongnet@163.com', $userName);
            $to = 'yanzongnet@163.com';
            $message->to($to)->subject('人工群发消息自动发送邮件');
        });

        return $response;
    }

    /**
        * 根据渠道id获取对应公众号的用户分组信息
        *
        * @param $channelId 渠道id
        *
        * @return array
     */
    private function getUserGroupList($channelId)
    {
        $wechatToken = $this->getWechatToken($channelId);
        $returnData = array(
            'status' => 0,
            'msg' => '获取失败'
        );
        if ( empty($wechatToken) ) {
            $returnData['status'] = -1; 
            $returnData['msg'] = "无token，失败！";
            return $returnData;
        }

        // 获取列表
        $listUrl = 'https://api.weixin.qq.com/cgi-bin/groups/get?access_token=' . $wechatToken;
        $response = Curl::to($listUrl)
            ->get();
        if ( empty($response) ) {
            echo "无分组";
            return false;
        }
        $response = json_decode($response, true);
        return $response;
    }

    /**
        * 根据渠道id获取文章列表
        *
        * @param $channelId 渠道id
        * @param $offset 查询开始数
        * @param $limit 每次读取数量
        *
        * @return array
     */
    private function getArticleList($channelId, $offset=0, $limit=10)
    {
        $wechatToken = $this->getWechatToken($channelId);
        $returnData = array(
            'status' => 0,
            'msg' => '获取失败'
        );
        if ( empty($wechatToken) ) {
            $returnData['status'] = -1; 
            $returnData['msg'] = "无token，失败！";
            return $returnData;
        }

        // 获取列表
        $listUrl = 'https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token=' . $wechatToken;
        $postData = [
            'type' => 'news',
            'offset' => $offset,
            'count' => $limit
        ];
        $response = Curl::to($listUrl)
            ->withData($postData)
            ->asJson()
            ->post();
        if ( empty($response) ) {
            echo "无文章";
            return false;
        }
        $response = json_decode(json_encode($response), true);
        return $response;
    }

    /**
        * 获取对应渠道access_token
        *
        * @param $channelId 渠道id
        *
        * @return string
     */
    private function getWechatToken($channelId)
    {
        $response = Curl::to('http://tpassport.lifeq.com.cn/pass/auth/channel/token')
            ->withData( array( 'channelId' => $channelId ) )
            ->get();
        if ( empty($response) ) {
            return '';
        }
        $response = json_decode($response, true);
        return isset($response['response']['access_token']) ? $response['response']['access_token'] : '';
    }
}
