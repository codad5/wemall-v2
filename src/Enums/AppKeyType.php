<?php

namespace Codad5\Wemall\Enums;



use Firebase\JWT\JWT;

enum AppKeyType: string
{
    case web = 'web';
    case apps = 'app';

    function getConstraint(?string $url)
    {
        return match ($this){
            self::web => $url,
            self::apps => self::generateJWTConstraint()
        };
    }
    static function generateAppKey(){
        return strtolower(substr(self::formatKey(md5(uniqid(rand(), true))), 0, 21));
    }
    static function formatConstraint($constraint)
    {
        return str_starts_with($constraint, "app_c_") ? $constraint : "app_c_{$constraint}";
    }

    static function formatKey($key)
    {
        return str_starts_with($key, "shop_") ? $key : "shop_{$key}";

    }
    public static function generateJWTConstraint(string $aud = "www.test.com") : string
    {
        $key = $_ENV['JWT_KEY'];
        $alg = $_ENV['JWT_ALG'];
        $payload = [
            'iss' => 'http://example.org',
            'aud' => $aud,
            'iat' => 1356999524,
            'nbf' => 1357000000,
            'exp' => time() + 157788000,
        ];
        return self::formatConstraint(JWT::encode($payload, $key,  $alg));
    }
}
