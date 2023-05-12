<?php

namespace Codad5\Wemall\Controller;

use Codad5\Wemall\Enums\ShopType;
use Codad5\Wemall\Libs\Exceptions\AuthException;
use Codad5\Wemall\Libs\Exceptions\CustomException;
use Codad5\Wemall\Libs\Exceptions\ShopException;
use Codad5\Wemall\Libs\Utils\UserAuth;
use Codad5\Wemall\Libs\Validator;
use Codad5\Wemall\Libs\ViewLoader;
use Codad5\Wemall\Models\Product;
use Codad5\Wemall\Models\Shop;
use Trulyao\PhpRouter\HTTP\Request;
use Trulyao\PhpRouter\HTTP\Response;
use Codad5\Wemall\View\V1 as View;

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
    static function index(Request $req, Response $res)
    {
        try{
            $shop = Shop::find($req->params('id'));
            $form = View\Shop::load_html_form($shop->type->value);
            //load add product page
            return $res->send(ViewLoader::load('html/shop_home.php', ["request" => $req, "shop" => [...$shop->toArray(), 'from' => $form]]));
        }catch(\Exception $e){
            return $res->status(400)->send($e->getMessage());
        }
    }
    static function product_view(Request $req, Response $res)
    {
        try{
            //get the shop id
            ['id' => $id] = $req->params();
            $shop = Shop::find($req->params('id'))->withProducts()->toArray();
            $shop['form'] = function($product_type, $values = []){
                return View\Shop::load_html_form($product_type, ['values' => $values]);
            };
            //load add product page
            return $res->send(ViewLoader::load('html/products.php', ["request" => $req, "shop" => $shop, "products" => $shop['products']]));
        }catch(\Exception $e){
            return $res->redirect('/home?error='.$e->getMessage());

        }
    }
    static function upload_product(Request $req, Response $res)
    {
        $shopId = $req->params('id');
        try{
            $shop = Shop::find($shopId);
            if(!$shop) throw new ShopException('Shop not found');
            if (!Validator::validate_product_creation_data($shop->type, $req))
            throw new CustomException("Error is shop Data", 300);
            $product = Product::create($shop, $req->body());
            return $res->redirect("/shop/$shopId/product?success=Product successfully created");
        }catch(\Exception $e){
            return $res->redirect("/shop/$shopId/product?error=".$e->getMessage());

        }
    }
}