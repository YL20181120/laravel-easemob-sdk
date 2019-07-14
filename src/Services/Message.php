<?php
/**
 * Date: 2019/7/13 17:37
 * Copyright (c) Youjingqiang <youjingqiang@gmail.com>
 */

namespace YL20181120\Easemob\Services;


use YL20181120\Easemob\Exceptions\EasemobError;
use YL20181120\Easemob\Http\Client as Http;

class Message extends BaseService
{
    // 目标数组 用户，群，聊天室
    protected $target_array = ['users', 'chatgroups', 'chatrooms'];

    /**
     * 发送文本消息
     *
     * @param array $users [接收的对象数组]
     * @param string $target_type [类型]
     * @param string $message [内容]
     * @param string $send_user [消息发送者]
     * @param array $ext [消息扩展体]
     *
     * @return mixed
     * @throws EasemobError
     */
    public function sendMessageText($users, $target_type = 'users', $message = "", $send_user = 'admin', $ext = [])
    {
        if (!in_array($target_type, $this->target_array)) {
            throw new EasemobError('target_type 参数错误！');
        }

        $url    = $this->url . 'messages';
        $option = [
            'target_type' => $target_type,
            'target'      => $users,
            'msg'         => [
                'type' => 'txt',
                'msg'  => $message
            ],
            'from'        => $send_user
        ];

        // 是否有消息扩展
        if (!empty($ext)) {
            $option['ext'] = $ext;
        }

        return Http::auth('post', $url, $option);
    }


    /**
     * 发送图片消息
     *
     * @param array $users [接收的对象数组]
     * @param string $target_type [类型]
     * @param string $uuid [文件的uuid]
     * @param string $share_secret [文件的秘钥 上传后生产]
     * @param string $file_name [指定文件名]
     * @param int $width [宽]
     * @param int $height [高]
     * @param string $send_user
     *
     * @return mixed
     * @throws EasemobError
     */
    public function sendMessageImg($users, $target_type = 'users', $uuid, $share_secret, $file_name, $width = 480, $height = 720, $send_user = 'admin')
    {
        if (!in_array($target_type, $this->target_array)) {
            throw new EasemobError('target_type 参数错误！');
        }

        $url    = $this->url . 'messages';
        $option = [
            'target_type' => $target_type,
            'target'      => $users,
            'msg'         => [
                'type'     => 'img',
                'url'      => $this->url . 'chatfiles/' . $uuid,
                'filename' => $file_name,
                'secret'   => $share_secret,
                'size'     => [
                    'width'  => $width,
                    'height' => $height
                ]
            ],
            'from'        => $send_user
        ];

        return Http::auth('post', $url, $option);
    }


    /**
     * 发送语音消息
     *
     * @param array $users [接收的对象数组]
     * @param string $target_type [类型]
     * @param string $uuid [文件的uuid]
     * @param string $share_secret [文件的秘钥 上传后生产]
     * @param string $file_name [指定文件名]
     * @param int $length [长度]
     * @param string $send_user
     *
     * @return mixed
     * @throws EasemobError
     */
    public function sendMessageAudio($users, $target_type = 'users', $uuid, $share_secret, $file_name, $length = 10, $send_user = 'admin')
    {
        if (!in_array($target_type, $this->target_array)) {
            throw new EasemobError('target_type 参数错误！');
        }

        $url    = $this->url . 'messages';
        $option = [
            'target_type' => $target_type,
            'target'      => $users,
            'msg'         => [
                'type'     => 'audio',
                'url'      => $this->url . 'chatfiles/' . $uuid,
                'filename' => $file_name,
                'secret'   => $share_secret,
                'length'   => $length
            ],
            'from'        => $send_user
        ];

        return Http::auth('post', $url, $option);
    }

    /**
     * 发送视频消息
     *
     * @param array $users [接收的对象数组]
     * @param string $target_type [类型]
     * @param        $video_uuid [视频uuid]
     * @param        $video_share_secret [视频秘钥]
     * @param        $video_file_name [下载的时候视频名称]
     * @param int $length [长度]
     * @param int $video_length [视频大小]
     * @param        $img_uuid [缩略图]
     * @param        $img_share_secret [图片秘钥]
     * @param string $send_user [发送者]
     *
     * @return mixed
     * @throws EasemobError
     */
    public function sendMessageVideo($users, $target_type = 'users', $video_uuid, $video_share_secret, $video_file_name, $length = 10, $video_length = 58103, $img_uuid, $img_share_secret, $send_user = 'admin')
    {
        if (!in_array($target_type, $this->target_array)) {
            throw new EasemobError('target_type 参数错误！');
        }

        $url    = $this->url . 'messages';
        $option = [
            'target_type' => $target_type,
            'target'      => $users,
            'msg'         => [
                'type'         => 'video',
                'url'          => $this->url . 'chatfiles/' . $video_uuid,
                'filename'     => $video_file_name,
                'thumb_secret' => $video_share_secret,
                'length'       => $length,
                'file_length'  => $video_length,
                'thumb'        => $this->url . 'chatfiles/' . $img_uuid,
                'secret'       => $img_share_secret
            ],
            'from'        => $send_user
        ];

        return Http::auth('post', $url, $option);
    }


    /**
     * 消息透传
     *
     * @param array|string $users [接收的对象数组]
     * @param string $target_type [类型]
     * @param string $message [内容]
     * @param string $from [消息发送者]
     *
     * @return mixed
     * @throws EasemobError
     */
    public function sendMessagePNS($users, $target_type = 'users', $message = "", $from = 'admin')
    {
        $users = Arr::wrap($users);
        if (!in_array($target_type, $this->target_array)) {
            throw new EasemobError('target_type 参数错误！');
        }

        $url    = $this->url . 'messages';
        $option = [
            'target_type' => $target_type,
            'type'        => 'txt',
            'target'      => $users,
            'msg'         => [
                'type'   => 'cmd',
                'action' => $message
            ],
            'from'        => $from
        ];

        return Http::auth('post', $url, $option);
    }

    public function messageHistory($date)
    {
        $url = $this->url . 'chatmessages/' . $date;
        return Http::auth('get', $url);
    }
}
