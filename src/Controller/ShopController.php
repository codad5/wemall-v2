<?php

namespace Codad5\Wemall\Controller;

use Codad5\Wemall\Enums\AdminType;
use Codad5\Wemall\Enums\ShopType;
use Codad5\Wemall\Libs\Exceptions\AuthException;
use Codad5\Wemall\Libs\Exceptions\CustomException;
use Codad5\Wemall\Libs\Exceptions\ShopException;
use Codad5\Wemall\Libs\Utils\UserAuth;
use Codad5\Wemall\Libs\Validator;
use Codad5\Wemall\Libs\ViewLoader;
use Codad5\Wemall\Models\Product;
use Codad5\Wemall\Models\Shop;
use Codad5\Wemall\Models\User;
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
            $form = View\Shop::load_html_form($shop->type);
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

    static function settings_view(Request $req, Response $res): Response
    {
        try{
            //get the shop id
            ['id' => $id] = $req->params();
            $shop = Shop::find($id)->withAdmins()->toArray();
            //load add product page
            return $res->send(ViewLoader::load('html/shop_setting.php', ["request" => $req, "shop" => $shop, "admins" => $shop['admins']]));
        }catch(\Exception $e){
            var_dump($e);
            return $res->redirect('/home?error='.$e->getMessage());

        }
    }

    /**
     * @throws AuthException
     */
    static function add_admin_to_shop(Request $request, Response $response)
    {
        $shop_id = $request->params('id');
        try {
            if(empty($request->body('email'))) throw new \Exception('Please Put in a valid username/email');
            $shop = Shop::find($shop_id);
            $user = User::find($request->body('email'));
            if(!$user) throw new AuthException("User {$request->body('email')} dont Exist");
            if($shop->isAdmin($user)) throw new \Exception("{$request->body('email')} is already an admin");
            $shop->addUserAsAdmin($user, AdminType::tryFrom($request->body('level')));
            return $response->redirect("/shop/$shop_id/settings?success=Admin added");

        }catch (\Exception $e)
        {
            return $response->redirect("/shop/$shop_id/settings?error=".$e->getMessage());
        }
    }
}