<?php
namespace Codad5\Wemall\Model;
use Codad5\Wemall\Helper\Db;

class Shop{
    public $name;
    public $email;
    public $password;
    public $confirm_password;
    public $id;
    public $created_at;
    public $updated_at;
    public $deleted_at;
    public $table = 'shops';
    
    protected Db $conn;
    public function __construct()
    {
        $this->conn = new Db();
    }

    public function save($data)
    {
        $sql = "INSERT INTO $this->table (name, description, created_by,  email, unique_id, api_key, admins) VALUES (?, ?, ?, ?, ?, ?, ?)";
        return $this->conn->query_data($sql, [
            $data['name'],
            $data['description'],
            $data['created_by'],
            $data['email'],
            $data['unique_id'],
            $data['api_key'],
            $data['admins']
        ]);
        
        
    }

    public function get_shop_by(string $by, $value) : array|null
    {
        $sql = "SELECT * FROM $this->table WHERE $by = ?";
        $data = $this->conn->select_data($sql, [
            $value
        ]);
        return count($data) > 0 ? $data : null;
    }
    //get all shops by a specific user
    public function get_shops_where_admin_is(string $by) : array
    {
        $data = $this->get_all_shops();
        $return_data = [];
        foreach($data as $shop){
            $admins = json_decode($shop['admins']);
            if(in_array($by, $admins->all)){
                $return_data[] = $shop;
            }
        }
        return $return_data;
    }
    public function get_all_shops() : array|null
    {
        $sql = "SELECT * FROM $this->table;";
        return $this->conn->select_data($sql, [
            
        ]);
       
    }
    public function delete_shop($id)
    {
        $sql = "DELETE FROM $this->table WHERE unique_id = ?";
        return $this->conn->query_data($sql, [
            $id
        ]);
    } 
}