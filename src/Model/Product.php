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
            $sql = "INSERT INTO products (name, description, price, created_by, quantity, images, product_id, product_type, shop_id, discount, discount_type, active_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?,?,?); $sql;";
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
            throw new CustomException($th->getMessage(), 500);
        }
    }
}