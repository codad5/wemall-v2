<?php

namespace Codad5\Wemall\Enums;

enum StatusCode : int
{
    // HTTP status codes
    case SUCCESS = 200;
    case BAD_REQUEST = 400;
    case UNAUTHORIZED = 401;
    case FORBIDDEN = 403;
    case NOT_FOUND = 404;
    case INTERNAL_ERROR = 500;

    // Custom application-specific error codes

}
