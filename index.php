<?php
session_start();
require(__DIR__ . '/vendor/autoload.php');

use Codad5\Wemall\Libs\ErrorHandler;
use Codad5\PhpRouter\Router as Router;
use Codad5\PhpInex\Import as Import;


$errorHandler = new ErrorHandler('index.php', true);
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$router = new Router(__DIR__ . "/src/view/", "/");

$router->allowed(['application/json', 'application/xml', 'text/html', 'text/plain', 'application/x-www-form-urlencoded', 'multipart/form-data']);


/** @var Router $app_routes */
$app_routes = Import::this('src/Routes/APP.php');
/** @var Router $api_routes */
$api_routes = Import::this('src/Routes/API.php');


$router->use_route($api_routes);
$router->use_route($app_routes);

$router->serve();