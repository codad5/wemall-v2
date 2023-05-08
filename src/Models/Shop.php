<?php

namespace Codad5\Wemall\Models;


use Codad5\Wemall\Enums\AdminType;
use Codad5\Wemall\Enums\ShopType;
use Codad5\Wemall\Libs\Database;
use Codad5\Wemall\Libs\Exceptions\AuthException;
use Codad5\Wemall\Libs\Exceptions\CustomException;
use Codad5\Wemall\Libs\Exceptions\ShopException;
use Codad5\Wemall\Libs\Utils\UserAuth;

class Shop
{
    const TABLE = 'shops';
    readonly public string $shop_id;
    readonly public string $name;
    readonly string $email;
    readonly string $description;
    readonly SHopType $type;
    readonly bool $ready;
    readonly User $creator;
    readonly string $created_at;
    readonly Database $conn;
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
        $shop = $this->get_shop_by('shop_id', $id);
        if(!$shop) throw  new ShopException("shop not found", 400);
        $shop = $shop[0];
        $this->shop_id = $shop['shop_id'];
        $this->name = $shop['name'];
        $this->email = $shop['email'];
        $this->description = $shop['description'];
        $this->type = ShopType::tryFrom($shop['type']);
        $this->creator = User::find($shop['creator_id']);
        $this->created_at = $shop['created_at'];

    }

    /**
     * @throws CustomException
     */
    public function get_shop_by($by, $value): ?array
    {
        $data = $this->conn->where($by, $value);
        return $data && count($data) > 0 ? $data : null;
    }

    /**
     * @throws AuthException
     * @throws CustomException
     */
    public static function create(string $name, string $description, string $email, ShopType $type) : self
    {
        $self = new self;
        $shop_id = $self->generate_id();
        $creator = UserAuth::who_is_loggedin() ?? throw new AuthException('you need to be logged in to perform this operation');
        $sql = "INSERT INTO ".self::TABLE." (name, description,  email, type, shop_id, creator_id) VALUES (?, ?, ?, ?, ?, ?)";
        $data = Database::query($sql, [
            $name,
            $description,
            $email,
            $type->value,
            $shop_id,
            $creator->user_id
        ]);
        try{
            return (new self($shop_id))->addUserAsAdmin($creator, AdminType::super_admin);
        }
        catch (\Exception $e){
            (new self($shop_id))->delete();
            throw new ShopException("Something Went Wrong Adding you as Admin");
        }
    }
    public function delete(): ?\PDOStatement
    {
        return Database::query("DELETE FROM shops WHERE shop_id = ?", [$this->shop_id]);
    }
    static function shopExist($id)
    {
        $shop = new self;
        $data = $shop->get_shop_by('shop_id', $id) ??
            $shop->get_shop_by('email', $id) ;
        return $data ? $data[0] : false;
    }
    public static function all(): false|array
    {
        $query = "SELECT * FROM " . self::TABLE;
        return Database::query($query)->fetchAll();
    }

    protected function last_id() : int{
        if (isset($this->last_id))
            return $this->last_id;
        $data = $this->all();
        return $this->last_id = count($data) > 0 ? $data[0]['id'] : 0;
    }
    protected function generate_id() : string
    {
        return strtoupper(substr('S'.$this->last_id()."A".md5(uniqid(rand(), true)), 0, 10));
    }
    protected function generate_api_key() : string
    {
        return strtolower(substr('api_u_'.$this->last_id()."A".md5(uniqid(rand(), true)), 0, 21));
    }

    static function find($login): ?Shop
    {
        $shopExist = self::shopExist($login);
        return $shopExist ?  new self($shopExist['shop_id']) : null;
    }
    public function addUserAsAdmin(User $user, AdminType $level)
    {
        $sql = "INSERT INTO shop_admin (user_id, shop_id,  level) VALUES (?, ?, ?)";
        $data = Database::query($sql, [
            $user->user_id,
            $this->shop_id,
            $level->value
        ]);
        return $this;
    }
    function isAdmin(User $user)
    {
        $data = Database::query("SELECT * FROM shop_admin INNER JOIN  users ON shop_admin.user_id = users.user_id WHERE shop_id = ? AND user_id = ?", [$this->shop_id, $user->user_id])->fetch();
        if($data && count($data) > 0) return $data;
        return false;
    }

//    function getAdmins



}