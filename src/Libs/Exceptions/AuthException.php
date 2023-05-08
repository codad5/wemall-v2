<?php

namespace Codad5\Wemall\Libs\Exceptions;

use Exception;

class AuthException extends CustomException
{
    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}