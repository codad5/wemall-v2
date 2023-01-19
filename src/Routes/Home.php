<?php

 use Codad5\Wemall\Controller\V1\Products;
 use Codad5\Wemall\Helper\Helper;
 use \Trulyao\PhpRouter\Router as Router;
 use \Trulyao\PhpRouter\HTTP\Response as Response;
 use \Codad5\Wemall\Handlers\ResponseHandler as CustomResponse;
 use \Codad5\Wemall\Handlers\CustomException as CustomException;
 use \Trulyao\PhpRouter\HTTP\Request as Request;
 use \Codad5\Wemall\Controller\V1\{Lists, Users, Shops};
 use \Codad5\Wemall\Helper\Validator as Validator;
 use \Codad5\Wemall\View\V1 as View;
 
 $router = new Router(__DIR__ . "/src/view/", "/");

$router->get('/', function ($req, $res) {
    echo 'hello';
});

$router->get('/home',[Helper::class, "redirect_if_logged_out"], function(Request $req, $res){
    try{
        $shops = [];
        $user = Users::current_user();
        $shops = $user->withShops()->shops->to_array();
        // echo "<pre>";
        // var_dump($shops);
        // exit;
         return $res->send(Helper::load_view('html/home.php',
        [
        "errors" => [$req->query('error')],
        "success" => [$req->query('success')],
        "shops" => $shops
        ]));
    }
    catch(CustomException $e){
         return $res->send(Helper::load_view('html/home.php',
      [
       "errors" => [$req->query('error'), $e->getMessage()],
       "success" => [$req->query('success')],
       "shops" => []
    ]));
    }
    
});

//route to get app product of a particular shop


// logout route
$router->get('/logout', function (Request $req, Response $res) {
    session_destroy();
    $new_query = "";
    foreach($_GET as $query => $value){
        $new_query.="$query=$value&";
    }
    return $res->redirect('/login?'.$new_query);
});

$router->route('/signup')
->get(
    [Helper::class, "redirect_if_logged_in"],
    function(Request $req, Response $res){
    foreach ($req->query() as $key => $value) {
        $req->append($key, $value);
    }},function (Request $req, Response $res) {
       return $res->send(Helper::load_view('html/signup.php',
        [
        "errors" => [$req->query('error')],
        "success" => [$req->query('success')]
        ]));
    }
)
->post(function (Request $req, Response $res) {
    try{
            $user = new Users;
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
->get(
    [Helper::class, "redirect_if_logged_in"],
    function(Request $req, Response $res){
    foreach ($req->query() as $key => $value) {
        $req->append($key, $value);
    }},
    function (Request $req, Response $res) {
    return $res->send(Helper::load_view('html/login.php',
        [
        "errors" => [$req->query('error')],
        "success" => [$req->query('success')]
        ]));
})
->post(function (Request $req, Response $res) {
    try{
    $user = new Users();
    $user_data = $user->login($req);
    if($user_data){
        return isset($_COOKIE['redirect_to_login']) ? $res->redirect($_COOKIE['redirect_to_login']."?success=welcome back") : $res->redirect('/home?success=login successful');
    }
    return $res->redirect('/login?error=an error occured');
    }
    catch (Exception $e) {
        //throw $th;
        return $res->redirect('/login?error='.$e->getMessage()."on line".$e->getLine()." ".$e->getFile());
    }

});

$export = $router;