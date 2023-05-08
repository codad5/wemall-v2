<?php
namespace Codad5\Wemall\Libs;
;

use Codad5\Wemall\Libs\Exceptions\CustomException;
use Codad5\Wemall\Libs\Exceptions\ShopException;
use Codad5\Wemall\Libs\Exceptions\ValueException;
use Trulyao\PhpRouter\HTTP\Request;
use Codad5\Wemall\Enums\{ShopType};

class Validator{
    public static function validate_email($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    # check empty
    public static function validate_empty(array $data)
    {
        foreach ($data as $key => $value) {
            if (empty($value)) {
                return false;
                // throw new CustomException("Please fill in all fields", 400, [$key => "Please fill in all fields"]);
            }
        }
        return true;
    }
    # check if password is strong
    public static function validate_password(string $password)
    {
        if (strlen($password) < 8) {
            throw new CustomException("Password must be at least 8 characters", 400);
        }
        if (!preg_match("#[0-9]+#", $password)) {
            throw new CustomException("Password must include at least one number!", 400);
        }
        if (!preg_match("#[a-z]+#", $password)) {
            throw new CustomException("Password must include at least one letter!", 400);
        }
        if (!preg_match("#[A-Z]+#", $password)) {
            throw new CustomException("Password must include at least one CAPS!", 400);
        }
        if (!preg_match("#\W+#", $password)) {
            throw new CustomException("Password must include at least one symbol!", 400);
        }
        return $password;
    }
    # check if password and confirm password match
    public static function validate_password_match(string $password, string $confirm_password)
    {
        if ($password !== $confirm_password) {
            return false;
        }
        return true;
    }

    public static function validate_signup_data(Request $req)
    {
        return true;
    }
    public static function validate_login_data(Request $req)
    {
        return true;
    }

    /**
     * @throws ShopException
     * @throws ValueException
     */
    public static function validate_shop_creation_data(Request $req)
    {
        if($req->body('shop_name') == null) throw new ShopException('Shop Name required');
        if($req->body('email') == null) throw new ShopException('Shop Email required');
        if($req->body('type') == null) throw new ShopException('Shop type required');
        if(!filter_var($req->body('email'), FILTER_VALIDATE_EMAIL)) throw new ValueException('Invalid Email');
        if(!in_array(ShopType::tryFrom($req->body('type')), ShopType::cases())) throw new ShopException('Invalid Shop type ');
        return true;
    }
    public static function validate_product_data(Request $req, array $product_base_field)
    {
        return true;
    }
    

}