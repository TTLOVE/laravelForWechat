<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Article;
use Ixudra\Curl\Facades\Curl;

class ArticleController extends Controller
{
    /**
        * for test
     */
    //const CHANNEL_ID = 11;
    /**
        * for online
     */
    const CHANNEL_ID = 1;

    /**
        * 文章列表
        *
     */
    public function index()
    {
        $articleList = $this->getArticleList(self::CHANNEL_ID);
        $userGroupList = $this->getUserGroupList(self::CHANNEL_ID);
        return view('admin/article/index')->withArticles($articleList['item'])->withUserGroup($userGroupList['groups']);
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
    
        $response = $this->sendMsg($mediaId, $groupId, self::CHANNEL_ID);

        if ( isset($response['status']) && $response['status']!=1 ) {
            return redirect()->back()->withInput()->withErrors($response['msg']);
        } else {
            if ( isset($response['errcode']) && $response['errcode']==0 ) {
                echo "\n\n";
                var_export($response);
                echo "\n\n";
                exit;
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
        echo "\n\n";
        var_export($postData);
        echo "\n\n";
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
        *
        * @return array
     */
    private function getArticleList($channelId, $offset=0)
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
            'count' => 20
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
