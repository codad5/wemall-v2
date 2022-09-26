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
// to go to the home page 
$router->get('/home',[Helper::class, "redirect_if_logged_out"], function($req, $res){
    echo "home";
    // var_dump($_SESSION);
    $res->use_engine()->render('html/home.php', $req);
});
// to show a shop
$router->get('/shop/:id', function(Request $req, Response $res){
    try{
        ['id' => $id] = $req->params();
        $shop = shops::get_details_by_id($id);
        if(!$shop){
            throw new CustomException('Shop Dont Exist', 404);
        }
        foreach ($shop as $key => $value) {
            $req->append($key, $value);
        }
        return $res->use_engine()->render('html/show_shop.php', $req);
    }catch(Exception $e){
        return $res->status(400)->send($e->getMessage());
    }
    


});

//to create a shop
$router->post('/shop/create', [Helper::class, "redirect_if_logged_out"], function($req, $res){
    ["shop_name" => $name, "email" => $email, "description" => $description] = $req->body();
    var_dump($name, $email, $description);
    $user = new Users($_SESSION['username']);
    $shop = new Shops($name, $description, $email ,$user);
    $shop->validate_shop_data();
    $shop->create_shop();
    return $res->redirect('/home?success=shop created');
});

// signup post and get route
$router->route('/signup')
->get([Helper::class, "redirect_if_logged_in"],
    function(Request $req, Response $res){
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
    // [$username, $password, $email, $name] = $req->body();
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

#login post and get route
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


//  testing old api 

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