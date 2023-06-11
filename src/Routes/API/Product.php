<?php


use Codad5\Wemall\Controller\API\ProductController;
use Codad5\Wemall\Libs\ViewLoader;
use \Codad5\PhpRouter\Router as Router;
use \Codad5\PhpRouter\HTTP\Response as Response;
use \Codad5\PhpRouter\HTTP\Request as Request;
use Codad5\Wemall\Controller\API\AuthController;
use \Codad5\Wemall\Libs\Utils\UserAuth;
use \Codad5\Wemall\Libs\{ResponseHandler};

$router = new Router(__DIR__ . "/src/view/",  "/product");

$router->get("/", [ProductController::class, 'get_all_product']);
$router->get("/type/:type", [ProductController::class, 'get_all_product']);
$router->get("/:type/search", [ProductController::class, 'search_product']);
$router->route('/:id')
->get([ProductController::class, 'get_product']);

$export = $router;