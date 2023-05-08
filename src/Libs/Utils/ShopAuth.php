<?php

namespace Codad5\Wemall\Libs\Utils;

use Codad5\Wemall\Models\Shop;

class ShopAuth
{
    static function shop_is_valid($id) : ?Shop
    {
        return Shop::find($id);
    }

//    static function is_shop_admin_with_access($shop_id, $user_id, $min_)
}