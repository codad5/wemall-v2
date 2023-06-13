<?php
namespace Codad5\Wemall\Libs;
use Codad5\Wemall\Enums\AdminType;
use Codad5\Wemall\Enums\AppError;
use Codad5\Wemall\Enums\AppKeyType;
use Codad5\Wemall\Enums\StatusCode;
use Codad5\Wemall\Enums\UserError;
use Codad5\Wemall\Libs\Exceptions\CustomException;
use Codad5\Wemall\Libs\Utils\ShopAuth;
use Codad5\Wemall\Libs\Utils\UserAuth;
use Codad5\PhpRouter\HTTP\Request;
use Codad5\PhpRouter\HTTP\Response;
use Codad5\Wemall\Models\Apikey;

Class APIMiddleWare {
    public static function redirect_if_logged_out(Request $req, Response $res): Response
    {
        // setcookie('redirect_to_login', '', time() - 3600, '/');
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
            return $res->redirect('/home?error=Shop does not exist');
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

    static function cors(Request $req, Response $res)
    {
        try{
            $api_key = $req->header('wemall-api-key');
            if (!$api_key) throw new CustomException("UnAuthorized, no API KEY FOUND", StatusCode::UNAUTHORIZED);
            $key_info = Apikey::getKeyData(AppKeyType::formatKey($api_key));
            if(!$key_info) throw new CustomException("Invalid API KEY", UserError::INVALID_API_KEY);
            switch (AppKeyType::tryFrom($key_info['platform'])){
                case AppKeyType::apps :
                    if(isset($_SERVER['HTTP_REFERER'])) throw new CustomException("UnAuthorized, Request Type", StatusCode::UNAUTHORIZED);
                    $app_constraint = $req->header('wemall-app-constraint');
                    if(!$app_constraint) throw new CustomException("UnAuthorized, missing App Constraint", StatusCode::UNAUTHORIZED);
                    if (AppKeyType::formatConstraint($app_constraint) != AppKeyType::formatConstraint($key_info['app_constraint'])) throw new CustomException('Invalid App Constraint', StatusCode::UNAUTHORIZED);
                break;
                case AppKeyType::web:
                    if($_SERVER['HTTP_REFERER'] !== $key_info['app_constraint']) throw new CustomException("Url not Authorized", StatusCode::UNAUTHORIZED);
                break;
                default:
                    throw new CustomException("Something went wrong", AppError::INVALID_APP_KEY_TYPE);

            }
            return $req;
        }catch (\Exception $e)
        {
            ResponseHandler::sendErrorResponse($res, $e->getMessage(), $e->getCode());
            exit();
        }

    }

}
