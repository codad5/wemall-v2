<?php
namespace Codad5\Wemall\Helper;

use Codad5\Wemall\Helper\CustomException as CustomException;
use Trulyao\PhpRouter\HTTP\Request;

class Validators{
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

    public static function validate_shop_creation_data(Request $req)
    {
        return true;
    }
    public static function validate_product_data(Request $req, array $product_base_field)
    {
        return true;
    }
    

}