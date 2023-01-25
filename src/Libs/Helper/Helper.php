<?php
namespace Codad5\Wemall\Libs\Helper;
use Codad5\Wemall\Controller\V1\ShopController;
use Codad5\Wemall\Controller\V1\UserController;
use Trulyao\PhpRouter\HTTP\Request;
use Trulyao\PhpRouter\HTTP\Response;
$dontenv = \Dotenv\Dotenv::createImmutable(__DIR__.'/../../../');
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

    public static function hash_password($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
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
    
    //check if shop exists
    public static function shop_exists($id) {
        return ShopController::exist($id);
    }
    public static function is_user_shop_owner($shop_id, $user_id){
        if(!self::shop_exists($shop_id)){
            return false;
        }
        if(ShopController::is_shop_admin($shop_id, $user_id)){
            return true;
        }
        return false;
    }
    public static function set_notification_session($data)
    {
        $_SESSION['messages']['errors'][] = $_GET['error'] ?? null;
        $_SESSION['messages']['errors'] = array_merge($data['errors'] ?? [], $_SESSION['messages']['errors']);
        $_SESSION['messages']['success'][] = $_GET['success'] ?? null;
        $_SESSION['messages']['success'] = array_merge($data['success'] ?? [], $_SESSION['messages']['success']);
        $_SESSION['messages']['info'][] = $_GET['info'] ?? null;
        $_SESSION['messages']['info'] = array_merge($data['info'] ?? [], $_SESSION['messages']['info']);
        $_SESSION['messages']['warning'][] = $_GET['warning'] ?? $_GET['warn'] ?? null;
        foreach($_SESSION['messages'] as $key => $value){
            $_SESSION['messages'][$key] = array_unique($value ?? []);
            // filter all null values
            $_SESSION['messages'][$key] = array_filter($_SESSION['messages'][$key], function($value){
                return $value !== null;
            });
            $_SESSION['messages'][$key] = count($_SESSION['messages'][$key]) > 0 ? $_SESSION['messages'][$key] : null;
        }
    }
    public static function add_notification($type, $message)
    {
        $_SESSION['messages'][$type][] = $message;
    }
}
