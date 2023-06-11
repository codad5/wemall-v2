<?php

namespace Codad5\Wemall\Libs\Exceptions;

class ProductException extends CustomException
{
    const PRODUCT_NOT_FOUND = 404;
    public function __construct($message, $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}