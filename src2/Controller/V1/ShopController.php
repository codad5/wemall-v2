<?php
namespace Codad5\Wemall\Controller\V1;

use Codad5\Wemall\Libs\Exceptions\CustomException;
use Codad5\Wemall\Libs\Validator;
use Codad5\Wemall\Model\Shop as Shop;
use Codad5\Wemall\Model\User;
use Trulyao\PhpRouter\HTTP\Request;


class ShopController
{
    public function __construct()
    {

    }

    /**
     * @throws CustomException
     */
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

    /**
     * @throws CustomException
     */
    public static function delete(Request $req): false|array
    {
        return Shop::find($req->params('id'))->delete()->fetchAll();
    }

    public static function exist($id): array|Shop|null
    {
        return Shop::find($id);
    }

    /**
     * @throws CustomException
     */
    public static function load($id)
    {
        return self::exist($id) ?? throw new CustomException('Shop does not exist', 300);
    }
    public static function is_shop_admin($shop_id, $user_id, $level = null)
    {
        return Shop::has_access($shop_id, $user_id, $level);
    }

    /**
     * @throws CustomException
     */
    public static function add_user_to_shop($shop_id, string $user_email)
    {
        $shop = self::exist($shop_id) ?? throw new CustomException('Shop does not exist');
        if (!$shop->has_access($shop_id, UserController::current_user()?->unique_id)) throw  new CustomException("You dont have such privilege");
        /** @var User $user */
        $user = User::where('email' , $user_email)->first() ?? throw new CustomException("User with email $user_email does not exist");
        if ($shop->has_access($shop_id, $user?->unique_id)) throw  new CustomException("Already an admin ($user_email)");
        return $shop->add_admin($user);
    }
}
