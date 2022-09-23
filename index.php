<?php
use Codad5\Wemall\Helper\Helper;
session_start();
 require(__DIR__ . '/vendor/autoload.php');
 require(__DIR__ . '/src/index.php');
 $dontenv = \Dotenv\Dotenv::createImmutable(__DIR__);
 $dontenv->load();
 use \Trulyao\PhpRouter\Router as Router;
 use \Trulyao\PhpRouter\HTTP\Response as Response;
 use \Codad5\Wemall\Helper\ResponseHandler as CustomResponse;
 use \Codad5\Wemall\Helper\CustomException as CustomException;
 use \Trulyao\PhpRouter\HTTP\Request as Request;
 use \Codad5\Wemall\Controller\V1\{Lists, Users, Shops};
 use \Codad5\Wemall\Helper\Validator as Validator;

 


$router = new Router(__DIR__ . "/src/view/", "/");

$router->allowed(['application/json', 'application/xml', 'text/html', 'text/plain', 'application/x-www-form-urlencoded', 'multipart/form-data']);

$router->get('/home', function($req, $res){
    echo "home";
    // var_dump($_SESSION);
    $res->use_engine()->render('html/home.php', $req);
});

$router->route('/signup')
->get(
    function(Request $req, Response $res){
    if(Users::any_is_logged_in()){
        return $res->redirect('/home');
    }
    foreach ($req->query() as $key => $value) {
        $req->append($key, $value);
    }

},function (Request $req, Response $res) {
    return $res->use_engine()->render('html/signup.php', $req);
})
->post(function (Request $req, Response $res) {
    try{
    $name = $req->body('name');
    $username = $req->body('username');
    $email = $req->body('email');
    $password = $req->body('password');
    $confirm_password = $req->body('confirm_password');
    if ($password !== $confirm_password) {
        return $res->redirect('/signup?error=Password does not match');
    }
    $user = new Users($username, $password, $email, $name);
    $user->validate_signup_user_data();
    $user->create_user();
    return $res->redirect('/signup?success=user created');
    }
    catch (\Exception $e) {
        //throw $th;
        return $res->redirect('/signup?error='.$e->getMessage());
    }

});

#login route
$router->route('/login')
->get(
    [Helper::class, "redirect_if_logged_in"],
    function(Request $req, Response $res){
    foreach ($req->query() as $key => $value) {
        $req->append($key, $value);
    }},
    function (Request $req, Response $res) {
    return $res->use_engine()->render('html/login.php', $req);
})
->post(function (Request $req, Response $res) {
    try{
    $login = $req->body('login');
    $password = $req->body('password');
    $user = new Users($login, $password);
    $user->validate_login_user_data();
    $user->validate_login_user_data();
    $user_data = $user->login();
    if($user_data){
        $user->set_login_session($user_data);
        return $res->redirect('/home?success=login successful');
    }
    return $res->redirect('/login?error=an error occured');
    }
    catch (\Exception $e) {
        //throw $th;
        return $res->redirect('/login?error='.$e->getMessage());
    }

});

$router->post('/shop/create', function($req, $res){
    $name = $req->body('shop_name');
    $description = $req->body('description');
    $user = new Users($_SESSION['username']);
    $shop = new Shops($name, $description, $user);
    $shop->validate_shop_data();
    $shop->create_shop();
    return $res->redirect('/home?success=shop created');
});

$router->get('/api/v1/list/:filter/:keyword', function (Request $req, Response $res) {
    try {
        $list = new Lists($req->params('filter'), $req->params('keyword'));
        $data = $list->get_list();
        $data['server'] = $_SERVER;
        return CustomResponse::success($res, 'list gotten', $data);
    } catch (\Exception $e) {
        //throw $th;
        return CustomResponse::error($res, $e);
    }
});

$router->serve();