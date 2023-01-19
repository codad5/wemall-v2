<?php
require(__DIR__ . '/vendor/autoload.php');
session_start();
$dontenv = \Dotenv\Dotenv::createImmutable(__DIR__);
$dontenv->load();


//  use Exception;

use \Trulyao\PhpRouter\Router as Router;
use Codad5\Wemall\Handlers\ErrorHandler;
use \Trulyao\PhpRouter\HTTP\Response as Response;
use \Codad5\Wemall\Handlers\ResponseHandler as CustomResponse;
use \Codad5\Wemall\Helper\Helper;
 use \Codad5\Wemall\View\V1 as View;
use \Trulyao\PhpRouter\HTTP\Request as Request;
use \Codad5\Wemall\Controller\V1\{Lists, Users, Shops};
use \Codad5\PhpInex\Import as Import;

$errorHander = new ErrorHandler(__DIR__.'/error.log');


set_error_handler(function ($no, $message, $file, $line) use ($errorHander) {
    $errorHander->handle($no, $message, $file, $line);
});



$router = new Router(__DIR__ . "/src/view/", "/");

$router->allowed(['application/json', 'application/xml', 'text/html', 'text/plain', 'application/x-www-form-urlencoded', 'multipart/form-data']);

$shop_routes = Import::this('src/Routes/Shops');
$home_routes = Import::this('src/Routes/Home');

$router->use_route($home_routes);
$router->use_route($shop_routes);
// echo '<pre>';
// var_dump($router->routes);
// exit;
$router->route('/:id/product')
->get(function ($req, $res) {
    try {
        //get the shop id
        ['id' => $id] = $req->params();
        $shop = Shops::load($req->params('id'))->withProducts()->toArray();
        $shop['form'] = function ($product_type, $values = []) {
            return View\Shop::load_html_form($product_type, ['values' => $values]);
        };
        //load add product page
        return $res->send(Helper::load_view('html/products.php', ["request" => $req, "shop" => $shop, "products" => $shop['products']]));
    } catch (Exception $e) {
        return $res->redirect('/home?error=' . $e->getMessage());

    }
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