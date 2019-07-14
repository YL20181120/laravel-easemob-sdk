<?php
/**
 * Date: 2019/7/13 17:53
 * Copyright (c) Youjingqiang <youjingqiang@gmail.com>
 */

namespace YL20181120\Easemob;

use Illuminate\Support\Facades\Facade as LaravelFacade;
use YL20181120\Easemob\Services\ChatRoom;
use YL20181120\Easemob\Services\Friend;
use YL20181120\Easemob\Services\Group;
use YL20181120\Easemob\Services\User;

class Facade extends LaravelFacade
{
    public static function getFacadeAccessor()
    {
        return 'easemob.user';
    }

    /**
     * @return User
     * @author Jasmine2
     */
    public static function user()
    {
        return app('easemob.user');
    }

    /**
     * @return Friend
     * @author Jasmine2
     */
    public static function friend()
    {
        return app('easemob.friend');
    }

    /**
     * @return Group
     * @author Jasmine2
     */
    public static function group()
    {
        return app('easemob.group');
    }

    /**
     * @return ChatRoom
     * @author Jasmine2
     */
    public static function chatRoom()
    {
        return app('easemob.chat-room');
    }

    /**
     * @return Friend
     * @author Jasmine2
     */
    public static function conference()
    {
        return app('easemob.conference');
    }
}
