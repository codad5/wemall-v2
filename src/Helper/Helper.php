<?php
namespace Codad5\Wemall\Helper;
use Codad5\Wemall\Controller\V1\Users;
use Trulyao\PhpRouter\HTTP\Request;
use Trulyao\PhpRouter\HTTP\Response;
$dontenv = \Dotenv\Dotenv::createImmutable(__DIR__.'/../../');
$dontenv->load();

Class Helper {
    function gethost($url){
        $sepreated = explode('/', $url);
        return $sepreated[2] ?? false;
    }
    function fetch($url, $method, $data = null){
            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => $data['timeout'] ?? 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => $method,
                CURLOPT_HTTPHEADER => [
                    "X-RapidAPI-Host: ".gethost($_ENV[$data['host']]),
                    "X-RapidAPI-Key: ".$_ENV['RAPID_KEY']
                ],
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);


            if ($err) {
                return [
                    'status' => false,
                    'message' => $err
                ];
            }   
            
                return $response;

    }
    public static function resolve_public_asset(string $file) 
    {   
        $http = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
        //return absolute path to the file from server host
        return "$http://".$_SERVER['HTTP_HOST'] . '/asset/' . $file;
    }
    public static function resolve_asset($file)
    {
        return $_SERVER['DOCUMENT_ROOT']."/asset"."/$file";
    }
    public static function resolve_view(string $file) 
    {   
        // echo file_exists($_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR."asset".DIRECTORY_SEPARATOR."$file") ? "byeeee" : "calmmmmm";
        return $_SERVER['DOCUMENT_ROOT']."/src/view"."/$file";
    }

    public static function redirect_if_logged_out(Request $req, Response $res){
    // setcookie('redirect_to_login', '', time() - 3600, '/');
    if(!Users::any_is_logged_in()){
        //unset previous cookie
        //set cookie for url that was for 10mins
        $url = $_SERVER['REQUEST_URI'];
        //remove the query string
        $url = explode('?', $url)[0];
         if(!isset($_COOKIE['redirect_to_login'])){
            setcookie('redirect_to_login', $url, time() + (60 * 15), "/");
        }
        return $res->redirect('/logout?error=login required for this action ');
    }
    
    return $res;
    }
    public static function redirect_if_logged_in(Request $req, Response $res){
    if(Users::any_is_logged_in()){
        return $res->redirect('/home');
    }
    
    return $res;
    }

    // to load / render php files to html
    public static function load_view(string $file, array $data = []) : string
    {
        $file = self::resolve_view($file);
        if(!file_exists($file)){
            return self::load_error_page(404, "File not found");
        }
        self::set_notification_session($data);
        
        ob_start();
        $data = array_merge($data, [
            'asset' => function($file){
                return self::resolve_public_asset($file);
            },
            'error' => 200,
            "header" => function(array $data = [], $title = "Wemall"){
                return self::load_view('templates/header.php', array_merge([
                    'app_name' => $_ENV['APP_NAME'] ?? "Wemall",
                    'title' => $title
                ], $data));
            },
            "footer" => function(){
                return self::load_view('templates/footer.php');
            },
            "notification" => function($message = null, $type = "success"){
                echo self::load_view('templates/alerts.php', [
                    'message' => $message,
                    'type' => $type,
                    'success' => $_SESSION['messages']['success'] ?? null,
                    'errors' => $_SESSION['messages']['errors'] ?? null,
                    "info" => $_SESSION['messages']['info'] ?? null
                ]);
            },
            "include" => function($file, $data = []){
                return self::load_view($file, $data);
            },
        ]);
        extract($data);
        require $file;
        $content = ob_get_contents();
        ob_end_clean();
        unset($_SESSION['messages']);
        return $content;
    }

    public function get_ip_address() : string
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
    public static function load_error_page($code, $message){
        $data = [
            'code' => $code,
            'message' => $message
        ];
        return self::load_view('templates/error.php', $data);
    }
    //check if shop exists
    public static function shop_exists($id) {
        $shop = \Codad5\Wemall\Controller\V1\Shops::shop_exists($id);
        if($shop){
            return true;
        }
        return false;
    }
    //redirect if shop does not exist
    public static function redirect_if_shop_does_not_exist(Request $req, Response $res){
        if(!self::shop_exists($req->params('id'))){
            return $res->redirect('/home?error=Shop does not exist');
        }
        return $res;
    }
    
    public static function redirect_if_user_is_not_shop_owner(Request $req, Response $res){
        if(!self::is_user_shop_owner($req->params('id'), $_SESSION['user_unique'])){
            return $res->redirect('/home?error=You are not the owner of this shop');
        }
        return $res;
    }
    public static function is_user_shop_owner($shop_id, $user_id){
        if(!self::shop_exists($shop_id)){
            return true;
        }
        if(\Codad5\Wemall\Controller\V1\Shops::is_shop_first_admin($shop_id, $user_id)){
            return true;
        }
        return false;
    }
    public static function set_notification_session($data){
        $_SESSION['messages']['errors'][] = $_GET['error'] ?? null;
        $_SESSION['messages']['errors'] = array_merge($data['errors'] ?? [], $_SESSION['messages']['errors']);
        $_SESSION['messages']['success'][] = $_GET['success'] ?? null;
        $_SESSION['messages']['success'] = array_merge($data['success'] ?? [], $_SESSION['messages']['success']);
        $_SESSION['messages']['info'][] = $_GET['info'] ?? null;
        $_SESSION['messages']['info'] = array_merge($data['info'] ?? [], $_SESSION['messages']['info']);
        // $infos =  array_unique($_SESSION['messages']['info'] ?? []);
        // $_SESSION['messages']['info'] = count($infos) > 0 ? $infos : null;
        // $errors =  array_unique($_SESSION['messages']['errors'] ?? []);
        // $_SESSION['messages']['errors'] = count($errors) > 0 ? $errors : null;
        // $success =  array_unique($_SESSION['messages']['success'] ?? []);
        // $_SESSION['messages']['success'] = count($success) > 0 ? $success : null;
        foreach($_SESSION['messages'] as $key => $value){
            $_SESSION['messages'][$key] = array_unique($value ?? []);
            // filter all null values
            $_SESSION['messages'][$key] = array_filter($_SESSION['messages'][$key], function($value){
                return $value !== null;
            });
            $_SESSION['messages'][$key] = count($_SESSION['messages'][$key]) > 0 ? $_SESSION['messages'][$key] : null;
        }
    }
}
