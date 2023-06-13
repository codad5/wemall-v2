<?php
namespace Codad5\Wemall\Libs;
use Codad5\Wemall\Enums\AppError;
use Codad5\Wemall\Enums\StatusCode;
use Codad5\Wemall\Enums\UserError;
use Exception;
use Codad5\PhpRouter\HTTP\Response as Response;
use Predis\Client;


class ResponseHandler {
    public static function sendSuccessResponse(Response $res, $data, $options = []): Response
    {
        $response = [
            'success' => true,
            'message' => $options['message'] ?? 'success',
            'cache' => false,
            ...$options,
            'data' => $data
        ];
        if (isset($options['token'])) {
            header('Authorization', 'Bearer ' . $options['token']);
        }
        if (isset($options['cache_data']) && !isset($options['token'])){
            try{
                $client = new Client();
                $client->setex("route:{$options['cache_data']}", 360, json_encode($data));
            }
            catch (Exception  $e){
                (new ErrorHandler('predis.php', false))->handleException($e, true);
            }
        }
        unset($response['cache_data']);
        return $res->status()->json($response);
    }

    public static function sendErrorResponse(Response $res, string $errorMessage, StatusCode|UserError|AppError|int $statusCode = StatusCode::INTERNAL_ERROR): Response
    {
        if ($statusCode instanceof  StatusCode || $statusCode instanceof UserError || $statusCode instanceof AppError) $statusCode = $statusCode->value;
        $responseCode = $statusCode;
        if($responseCode > 599) $responseCode = 400;
        if($statusCode instanceof AppError) $responseCode = 500;
        $response = [
            'success' => false,
            'message' => $errorMessage,
            'code' => $statusCode,
        ];

        return $res->status($responseCode)->json($response);
    }
}
