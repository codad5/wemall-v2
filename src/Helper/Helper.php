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

    public static function redirect_if_logged_in(Request $req, Response $res){
    if(Users::any_is_logged_in()){
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