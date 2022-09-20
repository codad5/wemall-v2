<?php
  
 require(__DIR__ . '/vendor/autoload.php');
 require(__DIR__ . '/src/index.php');
 $dontenv = \Dotenv\Dotenv::createImmutable(__DIR__);
 $dontenv->load();
 use \Trulyao\PhpRouter\Router as Router;
 use \Trulyao\PhpRouter\HTTP\Response as Response;
 use \Codad5\Wemall\Helper\ResponseHandler as CustomResponse;
 use \Codad5\Wemall\Helper\CustomException as CustomException;
 use \Trulyao\PhpRouter\HTTP\Request as Request;
 use \Codad5\Wemall\View\V1\Lists as Lists;
 


$router = new Router(__DIR__ . "/src/view/html", "/");

$router->allowed(['application/json', 'application/xml', 'text/html', 'text/plain', 'application/x-www-form-urlencoded', 'multipart/form-data']);

$router->get('/signup', function ($req, $res) {
    // return isset($_SESSION['']) ? $res->use_engine()->render('signup.php')
    return $res->use_engine()->render('signup.php');
});

$router->get('/api/v1/list/:filter/:keyword', function (Request $req, Response $res) {
    try {
        $list = new Lists($req->params('filter'), $req->params('keyword'));
    } catch (\Throwable $th) {
        //throw $th;
    }
    return $res->send('cool');
});

$router->serve();