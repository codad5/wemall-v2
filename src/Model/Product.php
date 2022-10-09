<?php
namespace Codad5\Wemall\Model;
use \Codad5\Wemall\Helper\Db as Db;
use \Codad5\Wemall\Helper\CustomException as CustomException;
use \Codad5\Wemall\Helper\ResponseHandler as CustomResponse;

Class Product{
    private Db $db;
    public function __construct()
    {
        $this->db = new Db();
    }
    
    public function get_product_by_id(int $id)
    {
        try {
            $data = $this->db->select_data("SELECT * FROM products WHERE id = ?", [$id]);
            return $data[0];
        } catch (\Exception $th) {
            throw new CustomException($th->getMessage(), 500, null, $th);
        }
    }
    
    public function get_product_by_name(string $name)
    {
        try {
            $this->db->query("SELECT * FROM products WHERE name = :name");
            $this->db->bind(':name', $name);
            $this->db->execute();
            return $this->db->single();
        } catch (\Throwable $th) {
            throw new CustomException($th->getMessage(), 500);
        }
    }
    
    public function get_product_by_category(string $category)
    {
        try {
            $data = $this->db->select_data("SELECT * FROM products where product_category LIKE ? AND active_status != ?;", ["%$category%", "deleted"]);
            return $data;
        } catch (\Exception $th) {
            throw new CustomException($th->getMessage(), 500);
        }
    }
    
    public function get_product_by_subcategory(string $subcategory){
        try {
            $this->db->query("SELECT * FROM products WHERE subcategory = :subcategory");
            $this->db->bind(':subcategory', $subcategory);
            $this->db->execute();
            return $this->db->single();
        } catch (\Throwable $th) {
            throw new CustomException($th->getMessage(), 500);
        }
    }
    
    public function get_product_by_brand(string $brand){
        try {
            $this->db->query("SELECT * FROM products WHERE brand = :brand");
            $this->db->bind(':brand', $brand);
            $this->db->execute();
            return $this->db->single();
        } catch (\Throwable $th) {
            throw new CustomException($th->getMessage(), 500);
        }
    }
    
    public function get_product_by_price(int $price){
        try {
            $this->db->query("SELECT * FROM products WHERE price = :price");
            $this->db->bind(':price', $price);
            $this->db->execute();
            return $this->db->single();
        } catch (\Throwable $th) {
            throw new CustomException($th->getMessage(), 500);
        }
    }
    //create product
    public function create_product(string $sql, array $data, array $product_type_data)
    {
        try {
            $sql = "INSERT INTO products (name, description, price, created_by, quantity, images, product_id, product_type, shop_id, discount, discount_type, active_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?,?,?,?,?); $sql;";
            // var_dump($sql);
            // exit;
            return $this->db->query_data($sql,[
            $data['name'],
            $data['description'],
            $data['price'],
            $data['created_by'],
            $data['quantity'],
            json_encode($data['images']),
            $data['product_id'],
            $data['shop_type'],
            $data['shop_id'],
            $data['discount'],
            $data['discount_type'],
            "true",
            ...$product_type_data
        ]);
        } catch (\Throwable $th) {
            // echo $th->getMessage();
            // echo "on line: {$th->getLine()}";
            // echo "in File: {$th->getFile()}";
            // exit;
            throw new CustomException($th->getMessage()." on line: {$th->getLine()}"." in File: {$th->getFile()}", 500);
        }
    }
    public function get_all_shop_product(string $shop_id, $product_type_table, $product_type_id = "product_id")
    {
        try {
            // a sql query to inner join the product table and the product type table on product_id
            // $sql = "SELECT * FROM products INNER JOIN $product_type_table ON products.product_id = $product_type_table.$product_type_id WHERE products.shop_id = ? AND products.active_status != ?;";
            $sql = "SELECT * FROM products INNER JOIN $product_type_table ON products.product_id = $product_type_table.$product_type_id WHERE products.shop_id = ?;";
            var_dump($sql);
            $data = $this->db->select_data($sql, [$shop_id]);
            // $data = $this->db->select_data($sql, [$shop_id, "deleted"]);
            return $data;
        } catch (\Throwable $th) {
            throw new CustomException($th->getMessage(), 500);
        }
    }
    public function get_all_product()
    {
        try {
            $sql = "SELECT * FROM products WHERE active_status != ?;";
            $data = $this->db->select_data($sql, ["deleted"]);
            return $data;
        } catch (\Throwable $th) {
            throw new CustomException($th->getMessage(), 500);
        }
    }
    public function get_product($product_id)
    {
        try {
            // select product to inner join with product type table on product_id
            $sql = "SELECT * FROM products INNER JOIN product_type ON products.product_id = product_type.product_id WHERE products.product_id = ? AND products.active_status != ?;";
        } catch (\Throwable $th) {
            throw new CustomException($th->getMessage(), 500);
        }
    }
}