<?php

use Codad5\Wemall\Libs\APIMiddleWare;
use Codad5\Wemall\Libs\ErrorHandler;
use Codad5\PhpRouter\HTTP\Response as Response;
use Codad5\PhpRouter\Router as Router;
use Codad5\Wemall\Libs\ResponseHandler;
use Codad5\PhpRouter\HTTP\Request as Request;
use Codad5\PhpInex\Import as Import;
use Predis\{Client, ClientException, Connection, Connection\ConnectionException};


$errorHandler = new ErrorHandler('api.php', false);

$router = new Router(__DIR__ . "/src/view/",  '/api');

$router->allowed(['application/json', 'application/xml', 'application/x-www-form-urlencoded', 'multipart/form-data']);
$router->run([ApiMiddleWare::class, 'cors']);
$router->run(function (Request $request, Response $res) use ($errorHandler){
    try{
        $client = new Client();
        $route = $request->path();
        $cache = $client->get("route:$route");
        if ($cache && count($request->body()) == 0 && count($request->query()) == 0) {
            $cache = json_decode($cache, true);
            return ResponseHandler::sendSuccessResponse($res, $cache, ['cache' => true]);
        }
    }
    catch (\Exception $e)
    {
        $errorHandler->handleException($e);
        return $request;
    }
});

$product_route = Import::this('api/Product');
$shop_route = Import::this('api/Shop');
$customer_route = Import::this('api/Customer');

$router->use_route($product_route);
$router->use_route($shop_route);
$router->use_route($customer_route);

$router->get("/", function ($req, $res){
    return ResponseHandler::sendSuccessResponse($res, [
       "status" => "ok",
       "server" => $_SERVER,
    ], ['cache_data' => $req->path()]);
});



$export = $router;