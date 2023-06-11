<?php

namespace Codad5\Wemall\Enums;

enum ProductStatus: string
{
    case active = 'active';
    case sold_out = 'sold_out';
    case deleted = "deleted";
}
