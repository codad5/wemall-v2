<?php
namespace Codad5\Wemall\Controller\V1;

use Codad5\Wemall\Model\Shop as Shop;
use Codad5\Wemall\Helper\CustomException as CustomException;
use Codad5\Wemall\Helper\Validators as Validator;
use Codad5\Wemall\Model\User;
use Trulyao\PhpRouter\HTTP\Request;


class Shops
{
    public function __construct()
    {

    }
    public function create(Request $req)
    {
        if (!Validator::validate_shop_creation_data($req))
            throw new CustomException("Error is shop Data", 300);
        $user = User::get_currenct_loggedin();
        if (!$user)
            throw new CustomException('Server Error', 500);
        $shop = new Shop;
        $shop->name = $req->body('shop_name');
        $shop->email = $req->body('email');
        $shop->description = $req->body('description');
        $shop->shop_type = $req->body('type');
        $shop->create($user);
        return $this;
    }

    public static function delete(Request $req)
    {
        return Shop::find($req->params('id'))->delete();
    }

    public static function exist($id)
    {
        return Shop::find($id);
    }
   
    public static function load($id)
    {
        return self::exist($id) ?? throw new CustomException('Shop does not exist', 300);
    }
    public static function is_shop_admin($shop_id, $user_id, $level = null)
    {
        return Shop::has_access($shop_id, $user_id, $level);
    }
}
