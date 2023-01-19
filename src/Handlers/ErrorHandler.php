<?php
namespace Codad5\Wemall\Handlers;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;



class ErrorHandler{
    private Logger $logger;
    public function __construct($file)
    {
        
        $this->logger = new Logger('channel-name');
        $this->logger->pushHandler(new StreamHandler($file, Logger::DEBUG));
    }

    public function handle($no, $message, $file, $line)
    {   
        $this->logger->error("Error : $no, \n Message : $message, \n File: $file on line $line");
    }
}