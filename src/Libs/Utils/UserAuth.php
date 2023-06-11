<?php

namespace Codad5\Wemall\Libs\Utils;

use Codad5\Wemall\Libs\Exceptions\AuthException;
use Codad5\Wemall\Libs\Exceptions\CustomException;
use Codad5\Wemall\Models\User;

class UserAuth
{
    /**
     * @throws AuthException
     */
    static function set_user_session(User $user): User
    {
        if(!$user->ready()) throw new AuthException("user not ready");
        if(session_status() == PHP_SESSION_NONE) session_start();
        $_SESSION['username'] = $user->username;
        $_SESSION['name'] = $user->name;
        $_SESSION['email'] = $user->email;
        $_SESSION['user_id'] = $user->user_id;
        return $user;
    }

    static function who_is_loggedin() : User|null
    {
        if(!isset($_SESSION['user_id'])) return self::log_out_user();
        $user = User::find($_SESSION['user_id']);
        return isset($_SESSION['username']) &&
        isset($_SESSION['email']) &&
        isset($_SESSION['name']) ? $user : null;
    }

    private static function log_out_user(): null
    {
        if(session_status() == PHP_SESSION_NONE) session_start();
        session_unset();
        session_destroy();
        unset($_SESSION['username']);
        unset($_SESSION['email']);
        unset($_SESSION['name']);
        return null;
    }

}