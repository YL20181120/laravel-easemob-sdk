<?php
/**
 * Date: 2019/7/13 17:36
 * Copyright (c) Youjingqiang <youjingqiang@gmail.com>
 */

namespace YL20181120\Easemob\Services;


class BaseService
{
    const CACHE_NAME = 'easemob:token';

    protected $gateway = null;

    // 企业的唯一标识
    protected $org_name = null;

    // “APP”唯一标识
    protected $app_name = null;

    // 客户ID
    protected $client_id = null;

    // 客户秘钥
    protected $client_secret = null;

    // token缓存时间
    protected $token_cache_time = null;

    // url地址
    protected $url = null;

    public function __construct($config)
    {
        $this->org_name         = $config['org_name'];
        $this->app_name         = $config['app_name'];
        $this->client_id        = $config['client_id'];
        $this->client_secret    = $config['client_secret'];
        $this->token_cache_time = $config['token_cache_time'] ?? 600;
        $this->url              = sprintf('%s/%s/', $this->org_name, $this->app_name);
        $this->gateway          = $config['domain_name'];
    }

    public static function stringReplace($string)
    {
        $string = str_replace('\\', '', $string);
        $string = str_replace(' ', '+', $string);
        return $string;
    }
}
