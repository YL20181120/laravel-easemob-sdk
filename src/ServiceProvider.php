<?php

namespace YL20181120\Easemob;

/**
 * Date: 2019/7/13 17:07
 * Copyright (c) Youjingqiang <youjingqiang@gmail.com>
 */
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use YL20181120\Easemob\Services\ChatRoom;
use YL20181120\Easemob\Services\Conference;
use YL20181120\Easemob\Services\Friend;
use YL20181120\Easemob\Services\Group;
use YL20181120\Easemob\Services\Message;
use YL20181120\Easemob\Services\User;

class ServiceProvider extends LaravelServiceProvider
{
    public function boot()
    {
        $this->publishes([
            realpath(__DIR__ . '/../config/easemob.php') => config_path('easemob.php')
        ], 'config');
    }

    public function register()
    {
        $apps = [
            'user'       => User::class,
            'friend'     => Friend::class,
            'chat-room'  => ChatRoom::class,
            'group'      => Group::class,
            'conference' => Conference::class,
            'message'    => Message::class
        ];

        foreach ($apps as $name => $class) {
            $this->app->singleton("easemob.{$name}", function () use ($class) {
                return new $class(config('easemob'));
            });
        }

        $this->app->singleton('easemob.http', function () {
            $baseHost = config('easemob.domain_name');
            $client   = new Client([
                'base_uri' => $baseHost,
                'headers'  => [
                    'accept' => 'application/json'
                ]
            ]);
            return $client;
        });
    }
}
