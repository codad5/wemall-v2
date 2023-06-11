<?php
namespace Codad5\Wemall\Libs;
use Codad5\Wemall\Enums\AdminType;
use Codad5\Wemall\Libs\Utils\ShopAuth;
use Codad5\Wemall\Libs\Utils\UserAuth;
use Codad5\PhpRouter\HTTP\Request;
use Codad5\PhpRouter\HTTP\Response;

Class Middleware {
    public static function redirect_if_logged_out(Request $req, Response $res): Response
    {
        if(!UserAuth::who_is_loggedin()){
            //unset previous cookie
            //set cookie for url that was for 10mins
            $url = $_SERVER['REQUEST_URI'];
            //remove the query string
            $url = explode('?', $url)[0];
            if(!isset($_COOKIE['redirect_to_login'])){
                setcookie('redirect_to_login', $url, time() + (60 * 15), "/");
            }
            return $res->redirect('/logout?error=login required for this action ');
        }

        return $res;
    }
    public static function redirect_if_logged_in(Request $req, Response $res): Response
    {
        if(UserAuth::who_is_loggedin()){
            return $res->redirect('/home');
        }
        return $res;
    }

    //redirect if shop does not exist
    public static function redirect_if_shop_does_not_exist(Request $req, Response $res): Response
    {
        if(!ShopAuth::shop_is_valid($req->params('id'))){
            $res->redirect('/home?error=Shop does not exist');
        }
        return $res;
    }
    
    public static function redirect_if_user_is_not_shop_owner(Request $req, Response $res): Response
    {
        if(!$has_access = ShopAuth::is_shop_admin_with_access($req->params('id'), UserAuth::who_is_loggedin())){
            $res->redirect('/home?error=You are not the owner of this shop');
            exit();
        }
        $_SESSION["admin_level"] = intval($has_access['level']);
        return $res;
    }
    public static function redirect_if_user_is_not_super_admin(Request $req, Response $res): Response
    {
        if(!$has_access = ShopAuth::is_shop_admin_with_access($req->params('id'), UserAuth::who_is_loggedin(), AdminType::super_admin)){
            $res->redirect("/shop/{$req->params('id')}?error=You dont have the access");
            exit();
        }
        $_SESSION["admin_level"] = intval($has_access['level']);
        return $res;
    }
    public static function is_user_shop_owner($shop_id, $user_id): bool
    {
        if(!ShopController::exist($shop_id)){
            return false;
        }
        if(ShopController::is_shop_admin($shop_id, $user_id)){
            return true;
        }
        return false;
    }
    
}
