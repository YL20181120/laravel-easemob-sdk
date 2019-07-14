<?php
/**
 * Date: 2019/7/13 18:09
 * Copyright (c) Youjingqiang <youjingqiang@gmail.com>
 */

namespace YL20181120\Easemob\Services;

use YL20181120\Easemob\Http\Client as Http;

class Friend extends BaseService
{
    /**
     * 给用户添加好友
     *
     * @param $owner_username [主人]
     * @param $friend_username [朋友]
     *
     * @return mixed
     */
    public function addFriend($owner_username, $friend_username)
    {
        $url = $this->url . 'users/' . $owner_username . '/contacts/users/' . $friend_username;

        return Http::auth('POST', $url);
    }


    /**
     * 给用户删除好友
     *
     * @param $owner_username [主人]
     * @param $friend_username [朋友]
     *
     * @return mixed
     */
    public function delFriend($owner_username, $friend_username)
    {
        $url = $this->url . 'users/' . $owner_username . '/contacts/users/' . $friend_username;

        return Http::auth('DELETE', $url);
    }


    /**
     * 查看用户所以好友
     *
     * @param $user_name
     *
     * @return mixed
     */
    public function showFriends($user_name)
    {
        $url = $this->url . 'users/' . $user_name . '/contacts/users/';
        return Http::auth('GET', $url);
    }


    /**
     * 查看用户所有的群
     *
     * @param $user_name
     *
     * @return mixed
     */
    public function showGroups($user_name)
    {
        $url = $this->url . 'users/' . $user_name . '/joined_chatgroups/';
        return Http::auth('GET', $url);
    }
}
