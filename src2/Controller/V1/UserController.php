<?php
namespace Codad5\Wemall\Controller\V1;


use Codad5\Wemall\Libs\Exceptions\CustomException;
use Codad5\Wemall\Libs\Helper\Helper;
use Codad5\Wemall\Libs\Validator;
use Codad5\Wemall\Model\User as User;
use Trulyao\PhpRouter\HTTP\Request;

class UserController
{
    /**
     * @throws CustomException
     */
    public function signup(Request $req) : User
    {
        if (!Validator::validate_signup_data($req))
            throw new CustomException('Error Vslidating signup input', 300);
        $user = new User();
        $user->name = $req->body('name');
        $user->email = $req->body('email');
        $user->username = $req->body('username');
        $user->password = Helper::hash_password($req->body('password'));
        return $user->create();
    }

    /**
     * @throws CustomException
     */
    public function login(Request $req) : array|User|null
    {
        if (!Validator::validate_login_data($req))
            throw new CustomException('Error in Login Data', 300);
        $login = $req->body('login');
        $user = User::where('username', $login) ?? User::where('email', $login);
        $user = $user->first();
        if (!$user)
            throw new CustomException("User $login not found", 400);
        if(!password_verify($req->body('password'), $user->password)){
            throw new CustomException("Incorrect Credentials ", 200);
        }
        return User::find($user->id)->set_login_session();
    }
    public static function any_is_logged_in() : bool
    {
        return isset($_SESSION['username']) && 
        isset($_SESSION['email']) &&
        isset($_SESSION['name']) &&
        isset($_SESSION['user_id']) &&
        isset($_SESSION['user_unique']) &&
        User::find($_SESSION['user_id']);
    }

    public static function current_user() : array|User|null
    {
        return User::get_currenct_loggedin() ?? null;
    }

    

    
}