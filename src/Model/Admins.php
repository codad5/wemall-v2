<?php
namespace Codad5\Wemall\Model;

use Codad5\Wemall\Libs\Database;
use Codad5\Wemall\DS\lists;
use Codad5\Wemall\Libs\CustomException;

class Admins{

    private const TABLE = 'SHOP_ADMINS';
    protected Database $conn;
    readonly public User $user;
    protected Shop $shop;
    public string|int $level;
    public string|int $unique_id;

    public function __construct(User $user = null, Shop $shop = null)
    {
        $this->conn = new Database(self::TABLE);
        $this->user = $user;
        $this->shop = $shop;
        if ($user && $shop)
            $this->ready();
    }

    /**
     * Ready the Admin Object based on the db_data
     * @return Admins
     * @throws CustomException
     */
    protected function ready(): Admins
    {
        $data = $this->isAdminIn($this->shop->unique_id, $this->user->unique_id);
        $this->level = $data['level'];
        return $this;
    }

    /**
     * @throws CustomException
     */
    public static function isAdminIn($shop_id, $user_id, $level = null): false|array
    {
        $sql = "SELECT * FROM ".self::TABLE." WHERE user_id = ? AND shop_id = ?";
        $binding = [
            $user_id,
            $shop_id
        ];
        if($level){
            $sql.= "AND level = ?";
            $binding[] = $level;
        }
        $result = Database::query($sql, $binding)->fetchAll();
        if(empty($result)) return false;
        return $result;
    }

    public function toArray(): array
    {
        return [
            'level' => $this->level,
            ...$this->user->toArray()
        ];
    }
    public static function add(Shop $shop, User|array $user, $level = 0): Admins
    {
        $user_id = null;
        $user_name = null;
        if ($user instanceof User && isset($user->id)){
            $user_id = $user->unique_id;
            $user_name = $user->username;
        }
        elseif(isset($user['unique_id'])) {
            $user_id = $user['unique_id'];
            $user_name = $user['username'];
        }
        return self::save($shop, ['username' => $user_name, 'user_id' => $user_id, 'level' => $level]);
    }

    public static function save(Shop $shop, $data)
    {
        $sql = "INSERT INTO ".self::TABLE." (user_id, shop_id,  shop_name, user_name, level) VALUES (?, ?, ?, ?, ?)";
        $result = Database::query($sql, [
            $data['user_id'],
            $shop->unique_id,
            $shop->name,
            $data['username'],
            $data['level']
        ]);
        if($result) return new Admins(User::find($data['user_id']), $shop);
        return false;
    }

    public function get_all(): false|array
    {
        $sql = "SELECT * FROM ".self::TABLE." WHERE shop_id = ?";
        return $this->conn->query($sql, [
            $this->shop->id
        ])->fetchAll();
    }
    public function get_by($by, $value)
    {
        return $this->conn->where($by,$value);
    }
    
    public static function where($column, $value)
    {
        return (new lists((new Admins)->get_by($column, $value)));
    
    }

    public static function shops($value)
    {
        return self::where('user_id', $value)->map(function ($data) {
           return Shop::find($data['shop_id']);
        });
    }
    public static function list($shop_id) : Lists
    {
        return self::where('shop_id', $shop_id)->map(/**
         * @throws CustomException
         */ function ($data) {
            return User::find($data['user_id']);
        });;
    
    }
    
    public function delete($user_id = null): false|array
    {
        $array = [
            $this->shop->unique_id
        ];
        $sql = "DELETE FROM ".self::TABLE." WHERE shop_id = ?";
        if($user_id) {
            $sql = "DELETE FROM ".self::TABLE." WHERE shop_id = ? AND user_id = ?";
            $array[] = $user_id;
        }
        return $this->conn->query($sql, $array)->fetchAll();
    }

    
}
