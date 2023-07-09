<?php
session_start();
require(__DIR__ . '/vendor/autoload.php');

use Codad5\Wemall\Libs\ErrorHandler;
use Codad5\PhpRouter\Router as Router;
use Codad5\PhpInex\Import as Import;

$errorHandler = new ErrorHandler('index.php', true);
echo "test";
if (file_exists(__DIR__ . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}
echo "test";

$envVariables = getenv();
if (is_array($envVariables)) {
    foreach ($envVariables as $key => $value) {
        $_ENV[$key] = $value;
    }
}
echo "test";



$router = new Router(__DIR__ . "/src/view/", "/");
echo "test";

$router->allowed(['application/json', 'application/xml', 'text/html', 'text/plain', 'application/x-www-form-urlencoded', 'multipart/form-data']);

echo "test";

/** @var Router $app_routes */
$app_routes = Import::this('src/Routes/APP.php');
echo "test";
/** @var Router $api_routes */
$api_routes = Import::this('src/Routes/API.php');
echo "test";


$router->use_route($api_routes);
echo "test";
$router->use_route($app_routes);
echo "test";

$router->serve();
