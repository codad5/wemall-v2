<?php

use Codad5\PhpRouter\Router as Router;
use Codad5\Wemall\Controller\APP\{HomeController, ShopController};
use Codad5\Wemall\Libs\Middleware;

$router = new Router(__DIR__ . "/src2/view/", "/");

$router->run([APIMiddleWare::class, "redirect_if_logged_out"]);

$router->get('/', function ($req, $res) {
    echo 'hello';
});

$router->get('/home', [HomeController::class, 'home_page']);

//to create a shop
$router->post('/shop/create',[ShopController::class, 'create']);



$export = $router;