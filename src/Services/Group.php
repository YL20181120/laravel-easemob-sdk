<?php
/**
 * Date: 2019/7/13 17:38
 * Copyright (c) Youjingqiang <youjingqiang@gmail.com>
 */

namespace YL20181120\Easemob\Services;

use YL20181120\Easemob\Exceptions\EasemobError;
use YL20181120\Easemob\Http\Client as Http;
use Illuminate\Support\Arr;

class Group extends BaseService
{
    public function allGroups($limit = 10, $cursor = '')
    {
        $url    = $this->url . 'chatgroups';
        $option = [
            'limit'  => $limit,
            'cursor' => $cursor
        ];
        return Http::auth('GET', $url, $option);
    }

    /**
     * 获取群信息
     *
     * @param array $group_ids [群id]
     *
     * @return mixed
     */
    public function groups($group_ids)
    {
        $group_ids = Arr::wrap($group_ids);
        $url       = $this->url . 'chatgroups/' . implode(',', $group_ids);

        return Http::auth('get', $url);
    }


    /**
     * 创建群
     *
     * @param string $group_name [群名称]
     * @param string $group_description [群描述]
     * @param string $owner_user [群主]
     * @param array $members_users [成员]
     * @param bool $is_public [是否为公开群]
     * @param int $max_user [最大人数]
     * @param bool $is_approval [加群是否要批准]
     *
     * @return mixed
     */
    public function groupCreate(
        $group_name,
        $group_description = '描述',
        $owner_user,
        $members_users = [],
        $is_public = true,
        $max_user = 500,
        $is_approval = true,
        $is_need_confirm = false,
        $members_only = false,
        $allowinvites = false
    )
    {
        $url    = $this->url . 'chatgroups';
        $option = [
            "groupname"           => self::stringReplace($group_name),
            "desc"                => self::stringReplace($group_description),
            "owner"               => (string)$owner_user,
            "public"              => boolval($is_public),
            "maxusers"            => $max_user,
            "approval"            => boolval($is_approval),
            'invite_need_confirm' => boolval($is_need_confirm),
            'members_only'        => boolval($members_only),
            'allowinvites'        => boolval($allowinvites)
        ];
        if (!empty($members_users)) {
            $option['members'] = $members_users;
        }

        return Http::auth('post', $url, $option);
    }


    /**
     * 修改群信息
     *
     * @param string $group_id
     * @param string $group_name
     * @param string $group_description
     * @param int $max_user
     *
     * @return mixed
     */
    public function groupEdit($group_id, $group_name = "", $group_description = "", $max_user = 0)
    {
        $url    = $this->url . 'chatgroups/' . $group_id;
        $option = [
            "groupname"   => self::stringReplace($group_name),
            "description" => self::stringReplace($group_description),
            "maxusers"    => $max_user,
        ];
        $option = array_filter($option);
        if (empty($option)) {
            throw new EasemobError('提交修改的参数，不修改提交空！');
        }

        return Http::auth('put', $url, $option);
    }


    /**
     * 删除群
     *
     * @param string $group_id
     *
     * @return mixed
     */
    public function groupDel(string $group_id)
    {
        $url = $this->url . 'chatgroups/' . $group_id;

        return Http::auth('delete', $url);
    }


    /**
     * 获取所有的群成员
     *
     * @param $group_id
     *
     * @return mixed
     */
    public function groupUsers(string $group_id)
    {
        $url = $this->url . 'chatgroups/' . $group_id . '/users';

        return Http::auth('get', $url);
    }


    /**
     * 添加群成员——批量
     *
     * @param string $group_id
     * @param array $users [用户名称 数组]
     *
     * @return mixed
     */
    public function groupAddUsers($group_id, array $users)
    {
        if (count($users) >= 60 || count($users) < 1) {
            throw new EasemobError('一次最多可以添加60位成员,最少为1个');
        }

        $url    = $this->url . 'chatgroups/' . $group_id . '/users';
        $option = [
            'usernames' => $users
        ];

        return Http::auth('post', $url, $option);
    }


    /**
     * 删除群成员-批量
     * 群主删除 必须先转让群
     *
     * @param string $group_id
     * @param array $users [用户名称 数组]
     *
     * @return mixed
     */
    public function groupDelUsers($group_id, array $users)
    {
        if (empty($users) || !is_array($users)) {
            throw new EasemobError('删除的用户不存在，或者提交参数不为数组！');
        }

        $url = $this->url . 'chatgroups/' . $group_id . '/users/' . implode(',', $users);

        return Http::auth('delete', $url);
    }


    /**
     * 获取用户所有参加的群
     *
     * @param $user
     *
     * @return mixed
     */
    public function userOfGroups($user)
    {
        $url = $this->url . 'users/' . $user . '/joined_chatgroups';

        return Http::auth('get', $url);
    }


    /**
     * 群转让
     *
     * @param $group_id [群Id]
     * @param $new_owner_user [新的群主]
     *
     * @return mixed
     */
    public function groupTransfer($group_id, $new_owner_user)
    {
        $url    = $this->url . 'chatgroups/' . $group_id;
        $option = [
            'newowner' => $new_owner_user
        ];

        return Http::auth('put', $url, $option);
    }

    /**
     * 查询群黑名单
     *
     * @param string $group_id [群Id]
     * @return mixed|\Psr\Http\Message\ResponseInterface|string
     */
    public function groupBlock(string $group_id)
    {
        $url = $this->url . 'chatgroups/' . $group_id . '/blocks/users';

        return Http::auth('get', $url);
    }

    /**
     * 添加用户到群黑名单--批量
     * @param string $group_id
     * @param array $users
     * @return mixed|\Psr\Http\Message\ResponseInterface|string
     */
    public function groupAddBlock(string $group_id, array $users)
    {
        if (count($users) >= 60 || count($users) < 1) {
            throw new EasemobError('一次最多可以添加60位成员,最少为1个');
        }

        $url = $this->url . 'chatgroups/' . $group_id . '/blocks/users';

        $option = [
            'usernames' => $users
        ];
        return Http::auth('post', $url, $option);
    }

    /**
     * 黑名单中移出用户-批量
     * @param $group_id
     * @param array $users
     * @return mixed|\Psr\Http\Message\ResponseInterface|string
     */
    public function groupDelBlock($group_id, array $users)
    {
        if (empty($users)) {
            throw new EasemobError('移除黑名单的用户不存在，或者提交参数不为数组！');
        }

        $url = $this->url . 'chatgroups/' . $group_id . '/blocks/users/' . implode(',', $users);

        return Http::auth('delete', $url);
    }

    /**
     * 获取群的管理员
     * @param $group_id
     * @return mixed|\Psr\Http\Message\ResponseInterface|string
     */
    public function groupGetAdmins($group_id)
    {
        $url = $this->url . 'chatgroups/' . $group_id . '/admin';

        return Http::auth('get', $url);
    }

    /**
     * 添加群管理员 -单个
     * @param $group_id
     * @param array $users
     * @return mixed|\Psr\Http\Message\ResponseInterface|string
     */
    public function groupAddAdmin($group_id, string $user)
    {
        $url    = $this->url . 'chatgroups/' . $group_id . '/admin';
        $option = [
            'newadmin' => $user
        ];
        return Http::auth('post', $url, $option);
    }

    /**
     * 移除群管理员 -单个
     * @param $group_id
     * @param string $user
     * @return mixed|\Psr\Http\Message\ResponseInterface|string
     */
    public function groupDelAdmin($group_id, string $user)
    {
        $url = $this->url . 'chatgroups/' . $group_id . '/admin/' . $user;

        return Http::auth('delete', $url);
    }
}
