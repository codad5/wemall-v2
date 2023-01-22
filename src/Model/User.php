<?php
namespace Codad5\Wemall\Model;

use Codad5\Wemall\Controller\V1\Users;
use Codad5\Wemall\DS\lists;
use Codad5\Wemall\Libs\CustomException;
use Codad5\Wemall\Libs\Database as Db;
Class User
{
    private  CONST TABLE = "users";
    public $username;
    public $name;
    public $api_key;
    public $email;
    public $password;
    protected $confirm_password;
    public $id;
    public $unique_id;
    public $created_at;
    public $updated_at;
    public $deleted_at;
    public $level;
    public Lists $shops;
    protected $conn;
    protected int $last_id;

    public function __construct($id = null)
    {
        $this->conn = new Db(self::TABLE);
        if ($id)
            $this->ready($id);
    }

    protected function ready($id){
        $data = $this->get_user_by('id', $id) ?? $this->get_user_by('unique_id', $id);
        if(!$data) return $this;
        $data = $data[0];
        
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->username = $data['username'];
        $this->email = $data['email'];
        $this->password = $data['password'];
        $this->unique_id = $data['unique_id'];
        $this->api_key = $data['api_key'];
        return $this;
    }
    public function withShops()
    {
        $this->shops = $this->shops ?? Admins::shops($this->unique_id);
        return $this;
    }
    protected function last_id() : int{
        if (isset($this->last_id))
            return $this->last_id;
        $data = $this->all();
        return $this->last_id = count($data) > 0 ? $data[0]['id'] : 0;
    }

    protected function generate_id()
    {
        return 'user_'.$this->last_id().substr(md5(uniqid(rand(), true)), 0, 12);
    }

    protected function generate_api_key()
    {
        return 'uK_'.$this->last_id().substr(md5(uniqid(rand(), true)), 0, 12);
    }

    public function create(){
        if ($this->conn->where('username', $this->username))
            throw new CustomException("Username : ($this->username) already in use", 300);
        if($this->conn->where('email', $this->email))
            throw new CustomException("email : ($this->email) already in use", 300);
        $this->unique_id = $this->generate_id();
        $this->api_key = $this->generate_api_key();
        $sql = "INSERT INTO ".self::TABLE." (name, username,  email, password, unique_id, api_key) VALUES (?, ?, ?, ?, ?, ?)";
        $this->conn->query($sql, [
            $this->name,
            $this->username,
            $this->email,
            $this->password,
            $this->unique_id,
            $this->api_key
        ]);
        return $this->ready(self::find($this->unique_id)->id);

    }
    public function save($data)
    {
        $sql = "INSERT INTO ".self::TABLE." (name, username,  email, password, unique_id, api_key) VALUES (?, ?, ?, ?, ?, ?)";
        return $this->conn->query($sql, [
            $data['name'],
            $data['username'],
            $data['email'],
            $data['password'],
            $data['unique_id'],
            $data['api_key']
        ]);
        
    }
    public function get_user_by(string $by, $value) : array|null
    {
        $data = $this->conn->where($by, [
            $value
        ]);
        return count($data) > 0 ? $data : null;
    }
    public function get_all_users() : array|null
    {
        return $this->conn->all();
    }

    public static function find($id)
    {   
        $data = (new User)->get_user_by('id', $id);
        if($data) return new User($id);
        $data = (new User)->get_user_by('unique_id', $id);
        if($data) return new User($id);
        return $data;
    }
    public static function where($column, $value)
    {
        return (new lists((new User)->get_user_by($column, $value)))->map(function ($data) {
            return User::find($data['id']);
        });
    
    }
    public static function all()
    {
        return Db::table(self::TABLE)->all();
    }
    public function set_login_session()
    {
        if(session_status() == PHP_SESSION_NONE) session_start();
            // check user exists
            if(!$this->find($this->id)){
                throw new CustomException("User Not Found", 200);
            }
            $_SESSION['username'] = $this->username;
            $_SESSION['name'] = $this->name;
            $_SESSION['email'] = $this->email;
            $_SESSION['user_id'] = $this->id;
            $_SESSION['user_unique'] = $this->unique_id;
        return $this;
    }
    public static function get_currenct_loggedin()
    {
        if (!Users::any_is_logged_in())
            return false;
        return self::find($_SESSION['user_id']);
    }
    public function toArray()
    {
        return [
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'unique_id' => $this->unique_id,
            'api_key' => $this->api_key
        ];
    }
}