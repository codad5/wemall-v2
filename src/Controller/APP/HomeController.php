<?php

namespace Codad5\Wemall\Controller\APP;

use Codad5\PhpRouter\HTTP\{Request, Response};
use Codad5\Wemall\Libs\Exceptions\CustomException;
use Codad5\Wemall\Libs\Utils\UserAuth;
use Codad5\Wemall\Libs\ViewLoader;

class HomeController
{
    static function home_page(Request $req, Response $res): Response
    {
        try{
            $user = UserAuth::who_is_loggedin();
            return $res->send(ViewLoader::load('html/home.php',
            [
                "errors" => [$req->query('error')],
                "success" => [$req->query('success')],
                "shops" => $user->getShopsAsArrays()
            ]));
        }
        catch(CustomException $e){
            return $res->send(ViewLoader::load('html/home.php',
                [
                    "errors" => [$req->query('error'), $e->getMessage()],
                    "success" => [$req->query('success')],
                    "shops" => []
                ]));
        }
    }
}