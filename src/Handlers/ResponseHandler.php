<?php
namespace Codad5\Wemall\Handlers;
use Codad5\Wemall\Handlers\CustomException;
use Exception;
use \Trulyao\PhpRouter\HTTP\Response as Response;

Class ResponseHandler{
    public static function success(Response $res, string $message, array $body = null, array $header = [], int $status_code = 200): Response
    {
        ResponseHandler::setHeader($header);
        return $res->status($status_code)->send([
            "success" => true,
            "message" => $message, 
            "data" => $body
        ]);
        
    }
    public static function error(Response $res, Exception|CustomException $e, array $header = []): Response
    {
        ResponseHandler::setHeader($header);
        $code = 500;
        $message = "Something is went wrong";
        $data = [];

        if($e instanceof CustomException){
            $code = $e->getCode();
            $message = $e->getMessage();
            $data = $e->getData();
        }
        $return_array = [
            "success" => false,
            "error" => $data,
            "message" => $e->getMessage(), 
            
        ];
        return $res->status($code)->send($return_array);
    }

    public static function setHeader(array $headers)
    {
        foreach($headers as $header => $value){
            header("$header: $value");
        }
    }

    // public static
}