<?php

namespace Codad5\Wemall\Libs\Utils;

use Codad5\Wemall\Enums\AdminType;
use Codad5\Wemall\Models\User;
use Codad5\Wemall\Models\Shop;

class ShopAuth
{
    static function shop_is_valid($id) : ?Shop
    {
        return Shop::find($id);
    }

    static function is_shop_admin_with_access($shop_id, User|int $user, AdminType $min_level = AdminType::admin){
        $shop = new Shop($shop_id);
        return $shop->isAdmin($user, $min_level);
    }
}