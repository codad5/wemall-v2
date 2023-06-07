<?php

namespace Codad5\Wemall\Controller;

use Codad5\Wemall\Libs\Exceptions\AuthException;
use Codad5\Wemall\Libs\Utils\UserAuth;
use Codad5\Wemall\Libs\Validator;
use Codad5\Wemall\Models\User;
use Codad5\PhpRouter\HTTP\Request;
use Codad5\PhpRouter\HTTP\Response;

class AuthController
{
    /**
     * @throws AuthException
     */
    static function signup(Request $req, Response $res)
    {
        try{
            if(!Validator::validate_signup_data($req)) throw new AuthException("Error validating signup data", 422);
            $name = $req->body('name');
            $email = $req->body('email');
            $username = $req->body('username');
            $password = $req->body('password');
            $user = new User;
            if($user->get_user_by('email', $email)) throw new AuthException("Email already in use", 404);
            if($user->get_user_by('username', $username)) throw new AuthException("Username already in use", 404);
            $user = $user->create($name, $username, $email, $password);
            return $res->redirect('/signup?success=user created');

        }
        catch (\Exception $e){
            return $res->status($e->getCode())->redirect('/signup?error='.$e->getMessage());
        }

    }

    /**
     * @throws AuthException
     */
    static function login(Request $request, Response $response)
    {
        try {
            if(!Validator::validate_login_data($request)) throw new AuthException('invalid login data', 422);
            $login = $request->body('login');
            $user = User::find($login);
            if (!$user)
                throw new AuthException("User $login not found", 400);
            if(!password_verify($request->body('password'), $user->password)){
                throw new AuthException("Incorrect Credentials ", 401);
            }
            UserAuth::set_user_session($user);
            return isset($_COOKIE['redirect_to_login']) ? $response->redirect($_COOKIE['redirect_to_login']."?success=welcome back") : $response->redirect('/home?success=login successful');

        }catch (\Exception $e)
        {
            return $response->status($e->getCode())->redirect('/login?error='.$e->getMessage());
        }
    }

}