<?php

namespace Codad5\Wemall\Models;

use Codad5\Wemall\Configs\Db;
use Codad5\Wemall\Libs\Database;
use Codad5\Wemall\Libs\Exceptions\AuthException;
use Codad5\Wemall\Libs\Exceptions\CustomException;

class User
{
    public string $name;
    public string $user_id;
    public string $username;
    public string $email;
    protected string $password;
    public string $api_key;
    public string $created_at;
    readonly private Database $conn;
    private int $last_id;
    private bool $ready;
    const TABLE = 'users';
    public function __construct(string $id = null)
    {
        $this->conn = new Database(self::TABLE);
        $this->ready = false;
        if($id)
            $this->ready($id);
    }

    public function ready(string $id = null)
    {
        if(!$id && isset($this->user_id)) $id = $this->user_id;
        if($this->ready) return $this;
        $data = $this->get_user_by('user_id', $id);
        if(!$data) Throw new AuthException('User not found');
        $data = $data[0];
        $this->user_id = $data['user_id'];
        $this->name = $data['name'];
        $this->username = $data['username'];
        $this->email = $data['email'];
        $this->api_key = $data['api_key'];
        $this->created_at = $data['created_at'];
        $this->password = $data['password'];
        $this->ready = true;
        return $this;
    }

    public function get_user_by($by, $value)
    {
        $data = $this->conn->where($by, $value);
        return $data && count($data) > 0 ? $data : null;
    }

    /**
     * @throws CustomException
     */
    public static function create(string $name, string $username, string $email, $password) : User
    {
        $self = new self;
        $user_id = $self->generate_id();
        $api_key = $self->generate_api_key();
        $password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO ".self::TABLE." (name, username,  email, password, user_id, api_key) VALUES (?, ?, ?, ?, ?, ?)";
        $data = Database::query($sql, [
            $name,
            $username,
            $email,
            $password,
            $user_id,
            $api_key
        ]);
        return new self($user_id);
    }
    static function userExist($id)
    {
        $id = trim($id);
        $user = new self;
        $data = $user->get_user_by('user_id', $id) ??
            $user->get_user_by('email', $id) ??
            $user->get_user_by('username', $id);
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
        return strtoupper(substr('U'.$this->last_id()."A".md5(uniqid(rand(), true)), 0, 10));
    }
    protected function generate_api_key() : string
    {
        return strtolower(substr('api_u_'.$this->last_id()."A".md5(uniqid(rand(), true)), 0, 21));
    }

    static function find($login): ?User
    {
        $user = self::userExist($login);
        return $user ?  new self($user['user_id']) : null;
    }

    /**
     * @return Shop[]
     * @throws CustomException
     */
    function getShops() : array
    {
        $shops = [];
        $shops_as_array = $this->getShopsAsArrays();
        foreach ($shops_as_array as $shop) {
            $shops[] = new Shop($shop['shop_id']);
        }
        return $shops;
    }
    function getShopsAsArrays() : array
    {
        return $this->conn->query("SELECT * FROM shop_admin INNER JOIN  shops ON shop_admin.shop_id = shops.shop_id WHERE user_id = ?", [$this->user_id])->fetchAll();
    }

    public function toArray(): array
    {
        return [];
    }
}