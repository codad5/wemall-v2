<?php

use Codad5\Wemall\Libs\APIMiddleWare;
use Codad5\Wemall\Libs\ViewLoader;
use Codad5\PhpRouter\Router as ShopRouter;
use Codad5\PhpRouter\HTTP\Response as Response;
use Codad5\PhpRouter\HTTP\Request as Request;
use Codad5\Wemall\Controller\API\{ProductController, ShopController};
use Codad5\Wemall\View\V1 as View;
use Codad5\Wemall\Libs\{Exceptions\ShopException, ResponseHandler, Utils\ShopAuth};
$router = new ShopRouter(__DIR__ . "/src/view/",  '/customer');


$router->get('/', function (Request $req, Response $res) {
    $res->send([
        "home" => 'test',
        "headers" => $req->headers(),
        'path' => $req->path()
    ]);
});





$export = $router;