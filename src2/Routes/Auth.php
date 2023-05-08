<?php

use Codad5\Wemall\Controller\V1\{Lists, UserController};
use Codad5\Wemall\Helper\Helper;
use Codad5\Wemall\Libs\ViewLoader;
use Trulyao\PhpRouter\HTTP\Request as Request;
use Trulyao\PhpRouter\HTTP\Response as Response;
use Trulyao\PhpRouter\Router as Router;

$router = new Router(__DIR__ . "/src2/view/", "/");

$router->run(function(Request $req, Response $res){
        if(UserController::any_is_logged_in()){
            return $res->redirect('/home');
        }
        return $res;
});

$router->run(function(Request $req, Response $res){
    foreach ($req->query() as $key => $value) {
        $req->append($key, $value);
    }
});


$router->route('/signup')
->get(function (Request $req, Response $res) {
       return $res->send(ViewLoader::load('html/signup.php',
        [
        "errors" => [$req->query('error')],
        "success" => [$req->query('success')]
        ]));
    }
)
->post(function (Request $req, Response $res) {
    try{
            $user = new UserController;
            $user->signup($req);
    return $res->redirect('/signup?success=user created');
    }
    catch (Exception $e) {
        //throw $th;
        return $res->redirect('/signup?error='.$e->getMessage());
    }

});

#login post and get route
$router->route('/login')
->get(function (Request $req, Response $res) {
    return $res->send(ViewLoader::load('html/login.php',
        [
        "errors" => [$req->query('error')],
        "success" => [$req->query('success')]
        ]));
})
->post(function (Request $req, Response $res) {
    try{
    $user = new UserController();
    $user_data = $user->login($req);
    if($user_data){
        return isset($_COOKIE['redirect_to_login']) ? $res->redirect($_COOKIE['redirect_to_login']."?success=welcome back") : $res->redirect('/home?success=login successful');
    }
    return $res->redirect('/login?error=an error occured');
    }
    catch (Exception $e) {
        //throw $th;
        return $res->redirect('/login?error='.$e->getMessage());
    }

});

$export = $router;