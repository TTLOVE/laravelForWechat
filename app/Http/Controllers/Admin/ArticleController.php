<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Ixudra\Curl\Facades\Curl;
use Cache;

class ArticleController extends Controller
{
    private $channelId = 11;

    public function __construct()
    {
        $userId = Auth::id();
        if ($userId == 2) {
            $this->channelId = 1;
        }
    }

    /**
     * 文章列表
     *
     */
    public function index()
    {
        $articleList   = $this->getArticleList($this->channelId);
        $userGroupList = $this->getUserGroupList($this->channelId);
        $pageNum       = 1;
        $pagePre       = $pageNum - 1;
        $pageNext      = $pageNum + 1;
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
        $offset  = ($pageNum - 1) * 10;
        // 获取列表
        $userGroupList = $this->getUserGroupList($this->channelId);
        $articleList   = $this->getArticleList($this->channelId, $offset);

        $pagePre  = $pageNum - 1;
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
        $mediaId    = $request->get('media_id');
        $groupIdArr = $request->get('group_id');

        if (empty($groupIdArr)) {
            return redirect()->back()->withInput()->withErrors('没有选中分组！');
        }

        $response = $this->sendMsg($mediaId, $groupIdArr, $this->channelId);

        if (isset($response['status']) && $response['status'] != 1) {
            return redirect()->back()->withInput()->withErrors($response['msg']);
        } else {
            if (isset($response['errcode']) && $response['errcode'] == 0) {
                $groupIdString = implode(",", $groupIdArr);
                return view('admin/article/send')->withMediaId($mediaId)->withGroupId($groupIdString)->withChannelId($this->channelId)->withMsgId($response['msg_id'])->withMsgDataId($response['msg_data_id']);
            } else {
                return redirect()->back()->withInput()->withErrors($response['errmsg']);
            }
        }
    }

    /**
     * 根据媒体id,群组id,渠道id发送消息
     *
     * @param $mediaId 媒体id
     * @param $groupIdArr 群组id数组
     * @param $channelId 渠道id
     *
     * @return array
     */
    private function sendMsg($mediaId, $groupIdArr, $channelId)
    {
        $wechatToken = $this->getWechatToken($channelId);
        if (empty($wechatToken)) {
            throw new \Exception("获取 token 失败", 1);
        }

        // 发送消息
        $sendUrl = 'https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token=' . $wechatToken;
        if (in_array('all', $groupIdArr)) {
            $postData = [
                "filter"              => [
                    "is_to_all" => true,
                    "group_id"  => 'all',
                ],
                "send_ignore_reprint" => 1,
                "mpnews"              => [
                    "media_id" => $mediaId,
                ],
                "msgtype"             => "mpnews",
            ];
            $response = $this->sendMsgAndEmail($sendUrl, $postData);
        } else {
            foreach ($groupIdArr as $groupId) {
                $postData = [
                    "filter"              => [
                        "is_to_all" => false,
                        "group_id"  => $groupId,
                    ],
                    "send_ignore_reprint" => 1,
                    "mpnews"              => [
                        "media_id" => $mediaId,
                    ],
                    "msgtype"             => "mpnews",
                ];
                $response = $this->sendMsgAndEmail($sendUrl, $postData);
            }
        }

        return $response;
    }

    /**
     * 群发消息并发送邮件给我
     *
     * @param $sendUrl 发送消息的链接
     * @param $postData 传输数据
     *
     * @return array
     */
    private function sendMsgAndEmail($sendUrl, $postData)
    {
        $response = Curl::to($sendUrl)
            ->withData($postData)
            ->asJson()
            ->post();

        if (empty($response)) {
            throw new \Exception("发送失败", 1);
        }

        return json_decode(json_encode($response), true);
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
        if (empty($wechatToken)) {
            throw new \Exception("获取 token 失败", 1);
        }

        // 获取列表
        $listUrl  = 'https://api.weixin.qq.com/cgi-bin/groups/get?access_token=' . $wechatToken;
        $response = Curl::to($listUrl)
            ->get();
        if (empty($response)) {
            throw new \Exception("获取  分组信息 失败", 1);
        }
        $response     = json_decode($response, true);
        $allSendGroup = [
            'id'    => 'all',
            'name'  => '全部发送',
            'count' => 0,
        ];
        array_unshift($response['groups'], $allSendGroup);
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
    private function getArticleList($channelId, $offset = 0, $limit = 10)
    {
        $wechatToken = $this->getWechatToken($channelId);
        if (empty($wechatToken)) {
            throw new \Exception("获取 token 失败", 1);
        }

        // 获取列表
        $listUrl  = 'https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token=' . $wechatToken;
        $postData = [
            'type'   => 'news',
            'offset' => $offset,
            'count'  => $limit,
        ];
        $response = Curl::to($listUrl)
            ->withData($postData)
            ->asJson()
            ->post();
        if (empty($response)) {
            throw new \Exception("获取 文章列表 失败", 1);
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
        $token = Cache::get('wechat_token');
        $token = @json_decode($token, true);

        if (empty($token) || $token['expire_time']<time()) {
            $response = Curl::to('https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx987894680b1d2f52&secret=ed85577df79c9c6003702b220f9a202a')
                ->get();
            $response = @json_decode($response, true);
            if (empty($response) || !isset($response['access_token'])) {
                throw new \Exception("获取token 失败", 1);
            }

            $token = [
                'token'       => $response['access_token'],
                'expire_time' => time() + 7000,
            ];
            Cache::put('wechat_token', json_encode($token));
        }

        return $token['token'];
    }
}
