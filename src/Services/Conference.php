<?php
/**
 * Date: 2019/7/13 17:38
 * Copyright (c) Youjingqiang <youjingqiang@gmail.com>
 */

namespace YL20181120\Easemob\Services;

use YL20181120\Easemob\Http\Client as Http;

class Conference extends BaseService
{
    /**
     * 创建会议
     *
     * @param $confrType
     * @param $password
     * @param $creator
     * @param int $memDefaultRole
     * @param bool $allowAudienceTalk
     * @param int $confrDelayMillis
     * @param bool $rec
     * @param bool $recMerge
     * @return array|\GuzzleHttp\Psr7\Response
     * @author Jasmine2
     */
    public function createConference(
        $confrType,
        $password,
        $creator,
        $memDefaultRole = 1,
        $allowAudienceTalk = true,
        $confrDelayMillis = 150,
        $rec = true,
        $recMerge = true
    )
    {
        $url = $this->url . 'conferences';
        return Http::auth('post', $url, compact(
            'confrType',
            'password',
            'creator',
            'confrDelayMillis',
            'memDefaultRole',
            'allowAudienceTalk',
            'rec',
            'recMerge'
        ));
    }

    /**
     * 结算会议
     * @param $conference_id
     * @return array|\GuzzleHttp\Psr7\Response
     * @author Jasmine2
     */
    public function dismissConference($conference_id)
    {
        $url = $this->url . 'conferences/' . $conference_id;
        return Http::auth('delete', $url);
    }

    /**
     * 会议详情
     * @param $conference_id
     * @return array|\GuzzleHttp\Psr7\Response
     * @author Jasmine2
     */
    public function viewConference($conference_id)
    {
        $url = $this->url . 'conferences/' . $conference_id;
        return Http::auth('get', $url);
    }


    /**
     * 从会议中删除用户
     * @param $conference_id
     * @param $username
     * @return array|\GuzzleHttp\Psr7\Response
     * @author Jasmine2
     */
    public function deleteConferenceUser($conference_id, $username)
    {
        $url = $this->url . "conferences/{$conference_id}/{$username}";
        return Http::auth('delete', $url);
    }
}
