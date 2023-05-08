<?php

namespace Codad5\Wemall\Controller;

use Codad5\Wemall\Enums\ShopType;
use Codad5\Wemall\Libs\Exceptions\AuthException;
use Codad5\Wemall\Libs\Exceptions\CustomException;
use Codad5\Wemall\Libs\Exceptions\ShopException;
use Codad5\Wemall\Libs\Utils\UserAuth;
use Codad5\Wemall\Libs\Validator;
use Codad5\Wemall\Models\Shop;
use Trulyao\PhpRouter\HTTP\Request;
use Trulyao\PhpRouter\HTTP\Response;

class ShopController
{
    /**
     * @throws AuthException
     * @throws CustomException
     * @throws ShopException
     */
    static function create(Request $req, Response $res)
    {
        try {
            if (!Validator::validate_shop_creation_data($req))
                throw new CustomException("Error is shop Data", 300);
            $name = $req->body('shop_name');
            $email = $req->body('email');
            $description = $req->body('description');
            $shop_type = $req->body('type');
            $user = UserAuth::who_is_loggedin();
            if(!$user) throw new AuthException("You need to login to do this", 401);
            $shop = new Shop();
            if ($shop->shopExist($email)) throw new ShopException('Email already in used ny another shop', 40);
            $shop->create($name, $description, $email, ShopType::tryFrom($shop_type));
            return $res->redirect('/home?success=user created');
        }
        catch (\Exception $e)
        {
            return $res->status($e->getCode())->redirect('/home?error='.$e->getMessage());
        }

    }
}