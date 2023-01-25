<?php
session_start();
require(__DIR__ . '/vendor/autoload.php');

use Codad5\Wemall\Libs\ErrorHandler;
use Trulyao\PhpRouter\HTTP\Response as Response;
use Trulyao\PhpRouter\Router as Router;
use Codad5\Wemall\Libs\ResponseHandler as CustomResponse;
use Codad5\Wemall\Libs\Helper\Helper;
use Trulyao\PhpRouter\HTTP\Request as Request;
use Codad5\PhpInex\Import as Import;


$errorHandler = new ErrorHandler('index.php', true);
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$router = new Router(__DIR__ . "/src/view/", "/");

$router->allowed(['application/json', 'application/xml', 'text/html', 'text/plain', 'application/x-www-form-urlencoded', 'multipart/form-data']);

$router->run(function () {
    if(session_status() !== PHP_SESSION_ACTIVE){
        session_unset();
        session_destroy();
        session_start();
    }
});

$router->run(function ($req, $res) {
    foreach($_GET as $query => $value){
        Helper::add_notification($query, $value);
    }
});

/** @var Router $shop_routes */
$shop_routes = Import::this('src/Routes/Shops');
/** @var Router $home_routes */
$home_routes = Import::this('src/Routes/Home');
/** @var Router $auth_routes */
$auth_routes = Import::this('src/Routes/Auth');

$router->use_route($home_routes);
$router->use_route($shop_routes);
$router->use_route($auth_routes);
// echo '<pre>';
// var_dump($router->routes);
// exit;

// logout route
$router->get('/logout', function (Request $req, Response $res) {
    session_destroy();
    $new_query = "";
    foreach($_GET as $query => $value){
        $new_query.="$query=$value&";
    }
    return $res->redirect('/login?'.$new_query);
});


$router->get('/api/v1/list/:filter/:keyword', function (Request $req, Response $res) {
    try {
        // $list = new Lists($req->params('filter'), $req->params('keyword'));
        // $data = $list->get_list();
        $data = $_SERVER;
        return CustomResponse::success($res, 'list gotten', $data);
    } catch (Exception $e) {
        //throw $th;
        return CustomResponse::error($res, $e);
    }
});



$router->serve();