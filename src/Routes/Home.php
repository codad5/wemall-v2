<?php

 use Codad5\Wemall\Libs\CustomException;
 use Codad5\Wemall\Libs\Middleware;
 use Codad5\Wemall\Libs\ViewLoader;
 use Trulyao\PhpRouter\Router as Router;
 use Trulyao\PhpRouter\HTTP\Response as Response;
 use Trulyao\PhpRouter\HTTP\Request as Request;
 use Codad5\Wemall\Controller\V1\{UserController, ShopController};
 
$router = new Router(__DIR__ . "/src/view/", "/");

$router->run([Middleware::class, "redirect_if_logged_out"]);

$router->get('/', function ($req, $res) {
    echo 'hello';
});

$router->get('/home', function(Request $req, $res){
    try{
        $shops = [];
        $user = UserController::current_user();
        $shops = $user->withShops()->shops->to_array();
         return $res->send(ViewLoader::load('html/home.php',
        [
        "errors" => [$req->query('error')],
        "success" => [$req->query('success')],
        "shops" => $shops
        ]));
    }
    catch(CustomException $e){
         return $res->send(ViewLoader::load('html/home.php',
      [
       "errors" => [$req->query('error'), $e->getMessage()],
       "success" => [$req->query('success')],
       "shops" => []
    ]));
    }
    
});

//to create a shop
$router->post('/shop/create', function(Request $req, Response $res){
    try{
    $shop = new ShopController;
    $shop->create($req);
    return $res->redirect('/home?success=shop created');
    }catch(Exception $e){
        return $res->redirect('/home?error='.$e->getMessage());

    }
});



$export = $router;