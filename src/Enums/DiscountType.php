<?php

namespace Codad5\Wemall\Enums;

use Codad5\Wemall\Libs\Exceptions\ProductException;

enum DiscountType: string
{
    case percentage = "percentage";
    case cut = "cut";

    /**
     * @throws ProductException
     */
    public function validate(int $discount, int $price): bool
    {
        switch ($this){
            case self::cut :
                if($discount > $price){
                    return false;
                }
            break;
            case self::percentage :
                if($discount > 100){
                    return false;
                }
            break;
        }
        return true;
    }
}
