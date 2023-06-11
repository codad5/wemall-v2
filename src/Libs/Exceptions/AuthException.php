<?php

namespace Codad5\Wemall\Libs\Exceptions;

use Codad5\Wemall\Enums\StatusCode;
use Exception;

class AuthException extends CustomException
{
    public function __construct($message, $code = StatusCode::INTERNAL_ERROR, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}