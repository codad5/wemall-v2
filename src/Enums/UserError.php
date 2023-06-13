<?php

namespace Codad5\Wemall\Enums;

enum UserError: int
{
    case INVALID_TYPE = 1001;
    case MISSING_APP_CONSTRAINT = 1002;
    const INVALID_API_KEY = 1003;
}
