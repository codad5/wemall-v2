<?php

use Codad5\Wemall\Libs\Exceptions\CustomException;
use Codad5\Wemall\Libs\APIMiddleWare;
use Codad5\Wemall\Libs\ViewLoader;
use Codad5\PhpRouter\HTTP\Request as Request;
use Codad5\PhpRouter\HTTP\Response as Response;
use Codad5\PhpRouter\Router as Router;
use \Codad5\Wemall\Libs\Utils\UserAuth;
use Codad5\Wemall\Controller\API\{ShopController, HomeController};

$router = new Router(__DIR__ . "/src2/view/", "/");

$router->run([APIMiddleWare::class, "redirect_if_logged_out"]);

$router->get('/', function ($req, $res) {
    echo 'hello';
});

$router->get('/home', [HomeController::class, 'home_page']);

//to create a shop
$router->post('/shop/create',[ShopController::class, 'create']);



$export = $router;