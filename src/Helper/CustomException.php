<?php
namespace Codad5\Wemall\Helper;
use Exception;
class CustomException extends Exception{
    private array|null $array_data = [];
    public function __construct(string $message, int $code, array $data = null, Exception $previous = null){
        parent::__construct($message, $code, $previous);
        $array_data = $data;

        
    }

    public function getData(): array|null
    {
        return $this?->array_data;
    }

}