<?php
/**
 * Date: 2019/7/13 17:38
 * Copyright (c) Youjingqiang <youjingqiang@gmail.com>
 */

namespace YL20181120\Easemob\Services;

use YL20181120\Easemob\Http\Client as Http;

class ChatRoom extends BaseService
{
    public function rooms($limit = 10, $cursor = '')
    {
        $url    = $this->url . 'chatrooms';
        $option = [
            'limit'  => $limit,
            'cursor' => $cursor
        ];
        return Http::auth('get', $url, $option);
    }

    /**
     * 获取一个聊天室详情
     *
     * @param $room_id
     *
     * @return mixed
     */
    public function room($room_id)
    {
        $url = $this->url . 'chatrooms/' . $room_id;

        return Http::auth('get', $url);
    }


    /**
     * 创建聊天室
     *
     * @param        $room_name
     * @param        $owner_name
     * @param string $room_description
     * @param int $max_user
     * @param array $member_users
     *
     * @return mixed
     */
    public function roomCreate($room_name, $owner_name, $room_description = "描述", $max_user = 200, $member_users = [])
    {
        $url    = $this->url . 'chatrooms';
        $option = [
            'name'        => $room_name,
            'description' => $room_description,
            'maxusers'    => $max_user,
            'owner'       => (string)$owner_name,
        ];
        if (!empty($member_users)) {
            $option['members'] = $member_users;
        }

        return Http::auth('post', $url, $option);
    }


    /**
     * 删除聊天室
     *
     * @param $room_id
     *
     * @return mixed
     */
    public function roomDel($room_id)
    {
        $url = $this->url . 'chatrooms/' . $room_id;

        return Http::auth('delete', $url);
    }


    /**
     * 修改聊天室信息
     *
     * @param string $group_id
     * @param string $group_name
     * @param string $group_description
     * @param int $max_user
     *
     * @return mixed
     * @throws EasemobError
     */
    public function roomEdit($room_id, $room_name = "", $room_description = "", $max_user = 0)
    {
        $url    = $this->url . 'chatrooms/' . $room_id;
        $option = [
            "name"        => self::stringReplace($room_name),
            "description" => self::stringReplace($room_description),
            "maxusers"    => $max_user,
        ];
        $option = array_filter($option);
        if (empty($option)) {
            throw new EasemobError('提交修改的参数，不修改提交空！');
        }

        return Http::auth('put', $url, $option);
    }


    /**
     * 获取用户所有参加的聊天室
     *
     * @param $user
     *
     * @return mixed
     */
    public function userToRooms($user)
    {
        $url = $this->url . 'users/' . $user . '/joined_chatrooms';

        return Http::auth('get', $url);
    }


    /**
     * 聊天室添加成员——批量
     *
     * @param string $room_id
     * @param array $users
     *
     * @return mixed
     */
    public function roomAddUsers($room_id, $users)
    {
        $url    = $this->url . 'chatrooms/' . $room_id . '/users';
        $option = [
            'usernames' => $users
        ];

        return Http::auth('post', $url, $option);
    }


    /**
     * 聊天室删除成员——批量
     *
     * @param string $room_id
     * @param array $users
     *
     * @return mixed
     */
    public function roomDelUsers($room_id, $users)
    {
        $url = $this->url . 'chatrooms/' . $room_id . '/users/' . implode(',', $users);

        return Http::auth('delete', $url);
    }
}
