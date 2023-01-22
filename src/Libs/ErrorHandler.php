<?php
namespace Codad5\Wemall\Libs;

use Codad5\Wemall\Libs\Helper\Helper;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class ErrorHandler
{
    private $logger;

    public function __construct($channel_name = 'MainErrorHandler', $main = false, $file = __DIR__ . '/../../logs/error.log')
    {
        $this->logger = new Logger($channel_name);
        $this->logger->pushHandler(new StreamHandler($file, Logger::ERROR));
        if($main){
            set_exception_handler([$this, 'handleException']);
            set_error_handler([$this, 'handleError']);
            register_shutdown_function([$this, 'handleShutdown']);
        }
    }

    public function handleException($e)
    {
        // Log the exception
        $this->logger->error($e->getMessage(), $this->getContext($e));
        // Return a custom response to the user
        if($_ENV['env'] == 'development' && $e->getCode() !== 500){
            Helper::add_notification('dev_warning', json_encode(['error' => $e->getMessage()]));
            ViewLoader::load_error_page(500, json_encode(['error' => $e->getMessage()]));
        }
    }
    public function getContext($e)
    {
        return [
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ];
    }
    public function handleError($errno, $errstr, $errfile, $errline)
    {
        // Log the error
        $this->logger->error("[$errno] $errstr in $errfile:$errline");
        // Return a custom response to the user
        if($_ENV['env'] == 'development'){
            Helper::add_notification('dev_warning', json_encode(['error' => $errstr]));
        }

    }

    public function handleShutdown()
    {
        $error = error_get_last();
        if ($error) {
            // Log the error
            $this->logger->error("[{$error['type']}] {$error['message']} in {$error['file']}:{$error['line']}");
            // Return a custom response to the user
            if($_ENV['env'] == 'development'){
                Helper::add_notification('dev_warning', json_encode(['error' => $error['message']]));
                ViewLoader::load_error_page(500, json_encode(['error' => $error['message']]));
            }else{
                ViewLoader::load_error_page(500, 'Something is wrong on our size');
            }
            exit;
        }
    }
}

