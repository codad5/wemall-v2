<?php
namespace Codad5\Wemall\Model;
use Codad5\Wemall\DS\lists;
use \Codad5\Wemall\Libs\Database as Db;
use \Codad5\Wemall\Libs\CustomException as CustomException;
use \Codad5\Wemall\Libs\ResponseHandler as CustomResponse;
use Codad5\Wemall\Model\ProductType\ProductType;
use Exception;

Class Product{
    private Db $conn;
    public $id;
    public $name;
    public $description;
    public $category;
    public $price;
    public $discount;
    public $discount_type;
    public User $created_by;
    public $quantity;
    public $quantity_left;
    public $images;
    public $product_id;
    public $product_type;
    public $active_status;
    public Shop $shop;
    public int|float $sell_price;
    public $shop_id;
    public $created_at;
    public ProductType $externals;
    protected $table = 'products';
    private const TABLE = 'products';
    const  SHOP_TYPE_ARRAY = ["Clothing"];
    public $data_array = [];
    protected $last_id;
    public function __construct($id = null, Shop $shop = null)
    {
        $shop ? $this->shop = $shop : null;
        $shop_id = $shop?->unique_id;
        $this->conn = new Db(self::TABLE);
        if ($id)
            $this->ready($id, $shop_id);
    }
      /**
       * This is to ready an abject based on the id
       * @param mixed $id
       * @param mixed $shop_id
       * @throws CustomException 
       * @return Product
       */

    protected function ready($id, $shop_id = null): static
    {
        $data = $this->get_by('id', $id) ?? $this->get_by('product_id', $id);
        if(!$data) return $this;
        $data = $data[0];
        if ($shop_id && $data['shop_id'] != $shop_id)
            throw new CustomException('Shop does not match product',400);
        $this->data_array = $data;
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->description = $data['description'];
        $this->category = $data['category'];
        $this->price = $data['price'];
        $this->discount = $data['discount'];
        $this->discount_type = $data['discount_type'];
        $this->quantity = $data['quantity'];
        $this->quantity_left = $this->quantity - 0;
        $this->images = $data['images'];
        $this->product_id = $data['product_id'];
        $this->product_type = $data['product_type'];
        $this->shop_id = $data['shop_id'];
        $this->active_status = $data['active_status'];
        $this->created_at = $data['created_at'];
        $this->externals = $this->loadExternal($this->product_type);
        $this->sell_price = $this->data_array['sell_price'] = $this->gen_sell_price();
        return $this;
        
    }
    /**
     * Summary of loadExternal --in use
     * @param mixed $product_type
     * @return ProductType
     */
    protected function loadExternal($product_type = null) : ProductType
    {
        $class_as_string = $this->getProductTypeClass($product_type ?? $this->product_type);
        if(!isset($this->externals) && (new $class_as_string) instanceof ProductType){
            $this->externals = new $class_as_string($this->product_id);
        }
        return $this->externals;
    }
    #NOTE: Not in use 
    public function getFullProduct($product_table)
    {
        $sql = "SELECT * FROM $this->table INNER JOIN $product_table ON {$this->table}.product_id = {$product_table}.product_id";
        $data = $this->conn->query($sql, []);
        return count($data) > 0 ? $data[0] : null;
    }
    /**
     * Attached the Shop to the product object
     * @return Product
     */
    public function withShop() : self
    {
        $this->shop = $this->shop ?? new Shop($this->data_array['shop_id']);
        return $this;
    }
    /**
     * Get the Shop Object Tied to the Product
     * @return Shop
     */
    public function getShop() : Shop
    {
        return $this->withShop()->shop;
    }
    /**
     * Get Product Class of the shop
     * @param string $product_type
     * @throws CustomException 
     * @return ProductType::class
     */
    public static function getProductTypeClass($product_type) : string
    {
        if(!in_array(ucfirst($product_type), self::SHOP_TYPE_ARRAY)){
            throw new CustomException("Invalid Product type $product_type", 404);
        }
        return  "Codad5\\Wemall\\Model\\ProductType\\".ucfirst($product_type);
    }
    /**
     * Generate a new product_id
     * @return string
     */
    protected function generateId()
    {
        return 'pr'.$this->last_id().substr(md5(uniqid(rand(), true)), 0, 8);
    }
    /**
     * Get the last PRIMARY KEY digit 
     * @return int
     */
    protected function last_id() : int
    {
        if (isset($this->last_id))
        return $this->last_id;
        $sql = "SELECT * FROM $this->table";
        $data = $this->conn->query($sql, [
            
        ]);
        return $this->last_id = count($data) > 0 ? $data[0]['id'] : 0;
    }
    /**
     * To create a new Product
     * @param Shop $shop the shop that owns the product 
     * @param array $data the `POST` requet data
     * @param array $images array of name of uploaded images
     * @throws CustomException 
     * @return Product
     */
    public function  create(Shop $shop, array $data, array $images)
    {
        $created_by = User::get_currenct_loggedin();
        if (!$created_by)
        throw new CustomException('You need to be logged in to perform this action', 400);
        if (!Shop::has_access($shop->unique_id, $created_by->unique_id))
        throw new CustomException('Can`t perform this action', 400);
        $unique_id = $this->generateId();
        $sql = "INSERT INTO $this->table (name, description, category, price, created_by, quantity, images, product_id, product_type, shop_id, discount, discount_type, active_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?,?,?,?,?);";
        // var_dump($sql);
        // var_dump($data);
        // exit;
        $this->conn->query($sql,[
            $data['name'],
            $data['description'],
            $data['category'],
            $data['price'],
            $created_by->unique_id,
            $data['quantity'],
            $images[0]['name'],
            $unique_id,
            $shop->shop_type,
            $shop->unique_id,
            $data['discount'],
            $data['discount_type'],
            true
        ]);
        try{
            (new ($this->getProductTypeClass($shop->shop_type)))->new($unique_id, $data);
            return $this->ready($unique_id);
        }
        catch(Exception $e){
            $this->delete();
            throw $e;
        }
    }
    /**
     * Delete a product
     * @param mixed $product_id the product id 
     * @return bool
     */
    public function delete($product_id = null)
    {
        $sql = "DELETE FROM $this->table WHERE product_id = ?";
        $this->conn->query($sql, [
            $product_id ?? $this->product_id
        ]);

        return $this->loadExternal()->delete($product_id);
    }
    /**
     * Get a product by
     * @param string $by
     * @param mixed $value
     * @return array|null
     */
    public function get_by(string $by, $value) : array|null
    {
        $sql = "SELECT * FROM $this->table WHERE $by = ?";
        $data = $this->conn->select($sql, [
            $value
        ]);
        
        return count($data) > 0 ? $data : null;
    }
     /**
      * Find a product based on the PIMARY KEY or unique id
      * @param mixed $id
      * @return Product|null
      */
     public static function find($id)
    {   
        $data = (new Product)->get_by('id', $id);
        if($data) return new Product($id);
        $data = (new Product)->get_by('product_id', $id);
        if($data) return new Product($id);
        return null;
    }
    /**
     * Find a product based on a specific field 
     * @param mixed $column
     * @param mixed $value
     * @return lists
     */
    public static function where($column, $value)
    {
        return (new lists((new Product)->get_by($column, $value)))->map(function ($data) {
            return new Product($data['id']);
        });
    
    }
    /**
     * Geneate selling price of the product
     * @return int
     */
    public function gen_sell_price()
    {
        switch($this->discount_type){
            case 'flat':
                return ($this->price - $this->discount);
            case 'percentage':
                $discount = ($this->discount / 100) * $this->price;
                return $this->price - $discount;
            default:
                return 0;
        }
    }
    /**
     * Get Category List
     * @return array<string>|bool
     */
    public function getCategoryList() 
    {
        return explode(',', $this->category);
    }
    /**
     * Get Products from a particular shop
     * @param mixed $shop_id
     * @return lists
     */
    public static function from($shop_id)
    {
        return self::where('shop_id', $shop_id);
    }
    /**
     * Attach the product creator to the object
     * @return Product
     */
    public function withCreator()
    {
        $this->created_by = $this->created_by ?? User::find($this->data_array['created_by']);
        $this->data_array['creator'] = $this->created_by->toArray();
        return $this;
    }
    /**
     * To Make the data array have the product entire field both of the product type and general product field
     * @return Product
     */
    public function merge()
    {
        $this->data_array = array_merge($this->data_array, $this->externals->toArray());
        return $this;
    }
    public function toArray(){
        return $this->merge()->data_array;
    }
   
}