<?php
namespace Codad5\Wemall\Model;
use \Codad5\Wemall\Helper\Db as Db;
use \Codad5\Wemall\Helper\CustomException as CustomException;
use \Codad5\Wemall\Helper\ResponseHandler as CustomResponse;

Class Product{
    public function __construct()
    {
        $this->db = new Db();
    }
    
    public function get_product_by_id(int $id){
        try {
            $data = $this->db->select_data("SELECT * FROM products WHERE id = ?", [$id]);
            return $data[0];
        } catch (\Throwable $th) {
            throw new CustomException($th->getMessage(), 500);
        }
    }
    
    public function get_product_by_name(string $name){
        try {
            $this->db->query("SELECT * FROM products WHERE name = :name");
            $this->db->bind(':name', $name);
            $this->db->execute();
            return $this->db->single();
        } catch (\Throwable $th) {
            throw new CustomException($th->getMessage(), 500);
        }
    }
    
    public function get_product_by_category(string $category){
        try {
            $this->db->query("SELECT * FROM products WHERE category = :category");
            $this->db->bind(':category', $category);
            $this->db->execute();
            return $this->db->single();
        } catch (\Throwable $th) {
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
            $this->db->query("SELECT * FROM products WHERE price = :price