<?php
namespace Codad5\Wemall\Libs\Exceptions;
use Codad5\Wemall\Libs\ErrorHandler;
use Codad5\Wemall\Libs\Helper\Helper;
use Exception;
use ReturnTypeWillChange;


class CustomException extends Exception
{
    private ErrorHandler $handler;
    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->handler = new ErrorHandler;
    }
    public function handle($message, $code)
    {
        var_dump("Error : $message in {$this->getFile()} on line {$this->getLine()}");
        if($code >= 500){
            $this->handler->handleException($this);
        }
    }

    #[ReturnTypeWillChange] public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}




// class CustomException extends Exception{
//     private array|null $array_data = [];
//     private $logger;
//     public function __construct(string $message, int $code, array $data = null, Exception $previous = null)
//     {
//         $this->logger = new Logger('channel');
//         $this->logger->pushHandler(new StreamHandler(__DIR__.'/../../error.log', Logger::DEBUG));
//         parent::__construct($message, $code, $previous);
//         $this->handle($message, $code);
//         $array_data = $data;

        
//     }
//     public function handle($message, $code)
//     {
//         var_dump("Error : $message in {$this->getFile()} on line {$this->getLine()}");
//         if($code >= 500){
//             $this->logger->error("Error : $message in {$this->getFile()} on line {$this->getLine()}");
//         }
//     }

//     public function getData(): array|null
//     {
//         return $this?->array_data;
//     }
    

// }