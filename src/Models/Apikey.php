<?php

namespace Codad5\Wemall\Models;

use Codad5\Wemall\Enums\AppKeyType;
use Codad5\Wemall\Enums\ShopType;
use Codad5\Wemall\Libs\Database;
use Codad5\Wemall\Libs\Exceptions\CustomException;
use Codad5\Wemall\Libs\Exceptions\ShopException;
use Codad5\Wemall\Libs\Utils\UserAuth;
use Firebase\JWT\JWK;
use Firebase\JWT\JWT;
use function Deployer\Support\starts_with;

class Apikey
{
    readonly string $key;
    readonly string $name;
    readonly AppKeyType $platform;
    readonly string $app_constraint;
    readonly Shop $shop;
    readonly User $creator;
    readonly string $expire;
    const TABLE = 'app_key';
    readonly Database $conn;
    private bool $ready;

    public function __construct(string $id = null)
    {
        $this->conn = new Database(self::TABLE);
        $this->ready = false;
        if($id)
            $this->ready($id);
    }

    /**
     * @throws CustomException
     * @throws ShopException
     */
    private function ready(string $id)
    {
        if(!$id) $id = $this->shop_id;
        if($this->ready) return $this;
        $key = $this->get_by('shop_id', $id);
        if(!$key) throw  new ShopException("shop not found", 400);
        $key = $key[0];
        $this->app_constraint = $key['app_constraint'];
        $this->name = $key['name'];
        $this->platform = AppKeyType::tryFrom($key['platform']);
        $this->shop = Shop::find($key['shop_id']);
        $this->creator = User::find($key['creator_id']);
        $this->expire = $key['expire'];
        $this->ready = true;

    }

    public function get_by(string $by, string $value)
    {
        return $this->conn->where($by, $value);
    }

    static function new(Shop $shop, AppKeyType $type, $appname, $constraint)
    {
        $user = UserAuth::who_is_loggedin();
        $token = AppKeyType::generateAppKey();
        $sql = "INSERT INTO ".self::TABLE." (app_name, app_key, app_constraint, platform, shop_id, creator_id, expire) VALuES (?,?,?,?,?,?,?);";
        Database::query($sql, [
            $appname,
            $token,
            $constraint,
            $type->value,
            $shop->shop_id,
            $user->user_id,
            date("Y-m-d", time() + 157788000)
        ]);
        return $token;
    }


    static function keyExist(Shop $shop, $id)
    {
        $sql = "SELECT * FROM ".self::TABLE." WHERE shop_id = ? AND app_constraint = ? OR app_key = ?";
        $data = Database::query($sql, [$shop->shop_id, AppKeyType::formatConstraint($id), AppKeyType::formatKey($id)]);
        return $data ? $data[0] : false;
    }

    static function getShopKeys(Shop $shop): false|array
    {
        $sql = "SELECT app_key.*, user.username AS creator FROM ".self::TABLE." AS app_key INNER JOIN users AS user ON app_key.creator_id = user.user_id WHERE app_key.shop_id = ?";
        return Database::query($sql, $shop->shop_id)->fetchAll();
    }

    public static function find(mixed $user_id)
    {
    }

}