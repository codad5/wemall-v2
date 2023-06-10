<?php
namespace Codad5\Wemall\Libs;
use Codad5\Wemall\Libs\Exceptions\CustomException;
use Exception;
use Codad5\PhpRouter\HTTP\Response as Response;
use Predis\Client;


class ResponseHandler {
    public static function sendSuccessResponse(Response $res, $data, $options = []) {
        $response = [
            'success' => true,
            'cache' => false,
            ...$options,
            'data' => $data
        ];
        if (isset($options['token'])) {
            header('Authorization', 'Bearer ' . $options['token']);
        }
        if (isset($options['cache_data']) && !isset($options['token'])){
            $client = new Client();
            $client->setex("route:{$options['cache_data']}", 360, json_encode($data));
            unset($response['cache_data']);
        }
        return $res->status(200)->json($response);
    }

    public static function sendErrorResponse(Response $res, string $errorMessage, $statusCode = 500) {
        $response = [
            'success' => false,
            'error' => $errorMessage,
        ];

        return $res->status($statusCode)->json($response);
    }
}
