<?php
namespace Codad5\Wemall\Model;

use Codad5\Wemall\Helper\CustomException as CustomException;
use Codad5\Wemall\Helper\Db as Db;
Class User
{
    public $name;
    public $email;
    public $password;
    public $confirm_password;
    public $id;
    public $created_at;
    public $updated_at;
    public $deleted_at;
    public $table = 'users';
    public $conn;

    public function __construct()
    {
        $this->conn = new Db();
    }

    public function save($data)
    {
        $sql = "INSERT INTO $this->table (name, username,  email, password, id) VALUES (?, ?, ?, ?, ?)";
        $this->conn->query_data($sql, [
            $data['name'],
            $data['username'],
            $data['email'],
            $data['password'],
            $data['id'],
        ]);
        ]);
    }
}