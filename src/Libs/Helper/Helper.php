<?php
namespace Codad5\Wemall\Libs\Helper;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__.'/../../../');
$dotenv->load();

Class Helper {
    function getHost($url): false|string
    {
        $separated = explode('/', $url);
        return $separated[2] ?? false;
    }
    function fetch($url, $method, $data = null): bool|array|string
    {
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
                    "X-RapidAPI-Host: ". $this->getHost($_ENV[$data['host']]),
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

    public static function hash_password($password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
    public static function resolve_public_asset(string $file): string
    {   
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
        //return absolute path to the file from server host
        return "$protocol://".$_SERVER['HTTP_HOST'] . '/asset/' . $file;
    }
    public static function resolve_asset($file): string
    {
        return $_SERVER['DOCUMENT_ROOT']."/asset"."/$file";
    }
    public static function resolve_view(string $file): string
    {
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
    public static function set_notification_session($data): void
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
    public static function add_notification($type, $message): void
    {
        $_SESSION['messages'][$type][] = $message;
    }
}
