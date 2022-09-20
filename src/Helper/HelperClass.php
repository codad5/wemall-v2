<?php
namespace Codad5\Wemall\Helper;

class HelperClass
{
    public static function gethost($url){
        $sepreated = explode('/', $url);
        return $sepreated[2] ?? false;
    }
    public static function fetch($url, $method, $data = null){
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
                "X-RapidAPI-Host: ".self::gethost($_ENV[$data['host']]),
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
}