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
    public static function resolve_asset(string $file) 
    {   
        // echo file_exists($_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR."asset".DIRECTORY_SEPARATOR."$file") ? "byeeee" : "calmmmmm";
        return $_SERVER['DOCUMENT_ROOT']."/asset"."/$file";
    }
    public static function resolve_view(string $file) 
    {   
        // echo file_exists($_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR."asset".DIRECTORY_SEPARATOR."$file") ? "byeeee" : "calmmmmm";
        return $_SERVER['DOCUMENT_ROOT']."/src/view"."/$file";
    }

    public static function redirect_if_logged_out(Request $req, Response $res){
    if(!Users::any_is_logged_in()){
        return $res->redirect('/login');
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
        $_SESSIO['errors'][] = $_GET['errors'] ?? [];
        $_SESSION['success'][] = $_GET['success'] ?? [];
        ob_start();
        $data = array_merge($data, [
            'asset' => function($file){
                return self::resolve_asset($file);
            },
            'error' => 200,
            "header" => function($title = "Wemall"){
                return self::load_view('templates/header.php', [
                    'app_name' => $_ENV['APP_NAME'] ?? "Wemall",
                    'title' => $title,
                    'success' => array_unique($_SESSION['success'] ?? []),
                    'errors' => array_unique($_SESSION['error'] ?? []),
                ]);
            },
            "footer" => function(){
                return self::load_view('templates/footer.php');
            },
        ]);
        extract($data);
        require $file;
        $content = ob_get_contents();
        ob_end_clean();
        unset($_SESSION['success']);
        unset($_SESSION['error']);
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
            return $res->redirect('/home');
        }
        return $res;
    }

}

// class axios {
//     public $method;
//     public function __construct(){

//     }
//     public function get($url, $headers = []){
        
//     }
// }