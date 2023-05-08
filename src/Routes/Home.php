<?php

use Codad5\Wemall\Libs\Exceptions\CustomException;
use Codad5\Wemall\Libs\Middleware;
use Codad5\Wemall\Libs\ViewLoader;
use Trulyao\PhpRouter\HTTP\Request as Request;
use Trulyao\PhpRouter\HTTP\Response as Response;
use Trulyao\PhpRouter\Router as Router;
use \Codad5\Wemall\Libs\Utils\UserAuth;
use \Codad5\Wemall\Controller\{ShopController, HomeController};

$router = new Router(__DIR__ . "/src2/view/", "/");

$router->run([Middleware::class, "redirect_if_logged_out"]);

$router->get('/', function ($req, $res) {
    echo 'hello';
});

$router->get('/home', [HomeController::class, 'home_page']);

//to create a shop
$router->post('/shop/create',[ShopController::class, 'create']);



$export = $router;