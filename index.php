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
 use \Codad5\Wemall\Controller\V1\Lists as Lists;
 use \Codad5\Wemall\Controller\V1\Users as Users;
 use \Codad5\Wemall\Helper\Validator as Validator;

 


$router = new Router(__DIR__ . "/src/view/", "/");

$router->allowed(['application/json', 'application/xml', 'text/html', 'text/plain', 'application/x-www-form-urlencoded', 'multipart/form-data']);

$router->route('/signup')
->get(
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
    if ($password !== $confirm_password) {
        return $res->redirect('/signup?error=Password does not match');
    }
    $user = new Users($username, $name, $email, $password);
    $user->validate_signup_user_data();
    $user->create_user();
    return $res->redirect('/signup?success=user created');
    }
    catch (\Exception $e) {
        //throw $th;
        return $res->redirect('/signup?error='.$e->getMessage());
    }

});

$router->get('/api/v1/list/:filter/:keyword', function (Request $req, Response $res) {
    try {
        $list = new Lists($req->params('filter'), $req->params('keyword'));
        $data = $list->get_list();
        return CustomResponse::success($res, 'list gotten', $data);
    } catch (\Exception $e) {
        //throw $th;
        return CustomResponse::error($res, $e);
    }
});

$router->serve();