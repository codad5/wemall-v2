<?php
session_start();
require(__DIR__ . '/vendor/autoload.php');

use Codad5\Wemall\Libs\ErrorHandler;
use Codad5\PhpRouter\HTTP\Response as Response;
use Codad5\PhpRouter\Router as Router;
use Codad5\Wemall\Libs\ResponseHandler as CustomResponse;
use Codad5\Wemall\Libs\Helper\Helper;
use Codad5\PhpRouter\HTTP\Request as Request;
use Codad5\PhpInex\Import as Import;


$errorHandler = new ErrorHandler('index.php', true);
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$router = new Router(__DIR__ . "/src2/view/", "/");

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
$shop_routes = Import::this('src/Routes/Shop');
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

//$key = $_ENV['JWT_KEY'];
//$alg = $_ENV['JWT_ALG'];
//$jwt = \Codad5\Wemall\Models\Apikey::generateApiToken("www.codad5.me");
//var_dump($jwt);
//sleep(6);
//var_dump(Firebase\JWT\JWT::decode(str_replace('shop_', '', $jwt), new \Firebase\JWT\Key($key, $alg)));
$router->serve();