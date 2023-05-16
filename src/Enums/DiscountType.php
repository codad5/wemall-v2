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
    public function getSellPrice(int $price, int $discount): float|int
    {
        if(!$this->validate($discount, $price)) throw new ProductException('Invalid Price');
        return match ($this) {
            self::cut => $price - $discount,
            self::percentage => $price - (($discount / 100) * $price),
            default => 0,
        };
    }
}
