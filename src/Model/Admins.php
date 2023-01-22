<?php
namespace Codad5\Wemall\Model;

use Codad5\Wemall\Libs\Database;
use Codad5\Wemall\DS\lists;
use Codad5\Wemall\Handlers\CustomException;

class Admins{

    private const TABLE = 'SHOP_ADMINS';
    protected Database $conn;
    protected Shop|null $shop;
    protected Lists $admins;

    public function __construct(Shop $shop = null)
    {
        $this->conn = new Database(self::TABLE);
        $this->shop = $shop;
        if ($shop)
            $this->ready();
    }
    /**
     * Ready the Admin Object based on the db_data
     * @return Admins
     */
    protected function ready()
    {
        $this->admins = (new Lists($this->get_all()))->map(function ($item) {
            $user = User::find($item['user_id']);
            $user->level = $item['level'];
            return $user;
        });

        return $this;
    }

    public function toArray()
    {
        return $this->admins->map(function (User $item) {
            return $item->toArray();
        })->to_array();
    }
    public function add(User|array $user, $level = 0)
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
        return $this->save(['username' => $user_name, 'user_id' => $user_id, 'level' => $level])->ready();
    }

    public function save($data)
    {
        $sql = "INSERT INTO $this->table (user_id, shop_id,  shop_name, user_name, level) VALUES (?, ?, ?, ?, ?)";
        $this->conn->query($sql, [
            $data['user_id'],
            $this->shop->unique_id,
            $this->shop->name,
            $data['username'],
            $data['level']
        ]);
        return $this;
    }

    public function get_all()
    {
        $sql = "SELECT * FROM $this->table WHERE shop_id = ?";
        return $this->conn->select_data($sql, [
            $this->shop->id
        ]);
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
        return self::where('shop_id', $shop_id)->map(function ($data) {
            User::find($data['user_id']);
        });;
    
    }
    
    public function delete($user_id = null)
    {
        $array = [
            $this->shop->unique_id
        ];
        $sql = "DELETE FROM $this->table WHERE shop_id = ?";
        if($user_id) {
            $sql = "DELETE FROM $this->table WHERE shop_id = ? AND user_id = ?";
            $array[] = $user_id;
        }
        return $this->conn->query_data($sql, $array);
    }


    
}
