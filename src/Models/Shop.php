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
    public bool $ready;
    readonly User $creator;
    /**
     * @var User[]
     */
    readonly array $admins;
    readonly string $created_at;
    readonly Database $conn;
    public array $products;
    public function __construct(string $id = null)
    {
        $this->conn = new Database(self::TABLE);
        $this->products = [];
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
        $this->ready = true;

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

    public function toArray(): array
    {
        return [
            'shop_id' => $this->shop_id,
            'name' => $this->name,
            'email' => $this->email,
            'description' => $this->description,
            'type' => $this->type,
            'creator_id' => $this->creator->user_id,
            'created_at' => $this->created_at,
            'products' => $this->products,
            'admins' => $this->admins ?? []
        ];
    }

    public function withProducts()
    {
        $this->products = Product::getProductFromShop($this) ?? [];
        return $this;
    }

    public function findProduct($product_id)
    {
        $product = Product::getProductFromShop($this, $product_id);
        if(!empty($product)) return Product::find($product[0]['product_id']);
        return false;
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

    function isCreator(User $user)
    {
        return $this->creator->user_id == $user->user_id;
    }
    public function addUserAsAdmin(User $user, AdminType $level)
    {
        $sql = "INSERT INTO shop_admin (user_id, shop_id, level, added_by) VALUES (?, ?, ?, ?)";
        $data = Database::query($sql, [
            $user->user_id,
            $this->shop_id,
            $level->value,
            UserAuth::who_is_loggedin()->user_id
        ]);
        return $this;
    }

    public function removeUserFromAdmin(User $user)
    {
        $sql = "DELETE  FROM shop_admin WHERE user_id = ? AND shop_id = ?";
        $data = Database::query($sql, [
            $user->user_id,
            $this->shop_id
        ]);
        return $this;
    }
    function isAdmin(User|int $user, AdminType $level = AdminType::admin)
    {
        $user_id = ($user instanceof User) ? $user->user_id : $user;
        return Database::query("SELECT * FROM shop_admin INNER JOIN  users ON shop_admin.user_id = users.user_id WHERE shop_id = ? AND shop_admin.user_id = ? AND level >= ?", [$this->shop_id, $user_id, $level->value])->fetch();

    }

    function withAdmins(): static
    {
        $this->admins =  $this->admins ?? $this->getAdmins($this);
        return $this;
    }

    public function getAdmins(Shop $shop ,AdminType $level = AdminType::admin, $as_array = true) : array
    {
        $admins = Database::query("SELECT shop_admin.*, users.*, addedby.username as added_by_username FROM shop_admin INNER JOIN  users ON shop_admin.user_id = users.user_id INNER JOIN users addedby ON shop_admin.added_by = addedby.user_id WHERE shop_id = ? AND level >= ? ORDER BY shop_admin.level DESC", [$shop->shop_id, $level->value])->fetchAll();
        if($as_array) return $admins;
        foreach ($admins as $index => $admin) {
            $admins[$index] = User::find($admin['user_id']);
        }
        return $admins;
    }

//    function getAdmins



}