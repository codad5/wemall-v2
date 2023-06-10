<?php


use Codad5\Wemall\Libs\ViewLoader;
use \Codad5\PhpRouter\Router as Router;
use \Codad5\PhpRouter\HTTP\Response as Response;
use \Codad5\PhpRouter\HTTP\Request as Request;
use Codad5\Wemall\Controller\API\API\AuthController;
use \Codad5\Wemall\Libs\Utils\UserAuth;

$router = new Router(__DIR__ . "/src2/view/", "/");
$router->run(function(Request $req, Response $res){
    if(UserAuth::who_is_loggedin()){
        return $res->redirect('/home');
    }
    return $res;
});

$router->run(function(Request $req, Response $res){
    foreach ($req->query() as $key => $value) {
        $req->append($key, $value);
    }
});

//login route
$router->route('/login')
->get(function(Request $request, Response $response) {
//    var_dump($_SESSION);
    return $response->send(ViewLoader::load('html/login.php',
    [
        "errors" => [$request->query('error')],
        "success" => [$request->query('success')]
    ]));
})
->post([AuthController::class, 'login']);

// login route
$router->route('/signup')
->get(function (Request $req, Response $res){
    return $res->send(ViewLoader::load('html/signup.php',
        [
            "errors" => [$req->query('error')],
            "success" => [$req->query('success')]
        ]));
})
->post([AuthController::class, 'signup']);

$export = $router;