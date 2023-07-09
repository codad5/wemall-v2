<?php
session_start();
require(__DIR__ . '/vendor/autoload.php');

use Codad5\Wemall\Libs\ErrorHandler;
use Codad5\PhpRouter\Router as Router;
use Codad5\PhpInex\Import as Import;

$errorHandler = new ErrorHandler('index.php', true);

if (file_exists(__DIR__ . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}

$envVariables = getenv();
if (is_array($envVariables)) {
    foreach ($envVariables as $key => $value) {
        $_ENV[$key] = $value;
    }
}



$router = new Router(__DIR__ . "/src/view/", "/");

$router->allowed(['application/json', 'application/xml', 'text/html', 'text/plain', 'application/x-www-form-urlencoded', 'multipart/form-data']);


/** @var Router $app_routes */
$app_routes = Import::this('src/Routes/APP.php');
/** @var Router $api_routes */
$api_routes = Import::this('src/Routes/API.php');


$router->use_route($api_routes);
$router->use_route($app_routes);

$router->serve();