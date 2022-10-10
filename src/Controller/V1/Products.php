<?php
namespace Codad5\Wemall\Controller\V1;
use Codad5\Wemall\Controller\V1\ProductType\Clothing;
use Codad5\Wemall\Controller\V1\ProductType\ProductInterface;
use Codad5\Wemall\Helper\CustomException;
use Codad5\Wemall\Helper\Helper;
use Codad5\Wemall\Model\Product;
use Codad5\FileHelper\FileUploader;

Class  Products
{
    private Shops|array $shop;
    private Shops|array $user;
    private array $data;
    protected string  $name;
    protected string $description = "";
    protected string  $id;
    protected string  $product_id;
    protected string  $price;
    protected string  $quantity;
    protected string  $discount;
    protected string $category;
    protected string  $discount_type;
    protected array $discount_array = ["percentage", "flat"];
    protected array $images;
    protected string $image_key = "images";
    const IMAGE_PATH = "images/products/";
    protected string $image_path = "asset/images/products/";
    protected Product $product_model;

    protected ProductInterface $shop_type;
    protected array $shop_types;
    public static array $shop_type_array = ["clothing"];

    public function __construct(Shops|array $shop, Users|array $user, array $data)
    {
        $this->shop = (array) $shop;
        $this->user = (array) $user[0];
        $this->data = $data;
        $this->product_model = new Product;
        $this->product_id = $this->generate_product_id();
        $shop_type = $this->assign_shop_type_object($shop['shop_type']);
        $shop_type = new $shop_type($this->shop, $this->user, $this->data);
        var_dump($shop['shop_type']);
        if($shop_type instanceof ProductInterface){
            $this->shop_type = $shop_type;
        }
        else{
            throw new CustomException("Invalid Shop Type", 500);
        }
        // exit;
    }

    protected static function assign_shop_type_object(string $type) : string
    {
       
        if(!in_array($type, self::$shop_type_array)){
            throw new CustomException("Invalid shop type", 404);
        }
        return $shop_type_class = "Codad5\\Wemall\\Controller\V1\ProductType\\".ucfirst($type);
        // return new $shop_type_class($this->shop, $this->user, $this->data) ?? null;
    }

    public function validate_product_data()
    {
        if(!isset($this->data['name']) || empty($this->data['name'])){
            throw new CustomException("Product name required", 303);
        }
        if(!isset($this->data['price']) || empty($this->data['price'])){
            throw new CustomException("Product price required", 303);
        }
        if(!isset($this->data['quantity']) || empty($this->data['quantity'])){
            throw new CustomException("Product quantity required", 303);
        }
        if(!isset($this->data['discount']) || empty($this->data['discount'])){
            throw new CustomException("Product discount required", 303);
        }
        if(!isset($this->data['category']) || empty($this->data['category'])){
            throw new CustomException("Product category type required", 303);
        }
        if(!isset($this->data['discount_type']) || empty($this->data['discount_type'])){
            throw new CustomException("Product discount type required", 303);
        }
        if(!in_array($this->data['discount_type'], $this->discount_array)){
            throw new CustomException("Invalid discount type", 303);
        }
        if($this->data['discount'] > 100 && $this->data['discount_type'] == "percentage"){
            throw new CustomException("Invalid discount value", 303);
        }
        if($this->data['discount'] > $this->data['price'] && $this->data['discount_type'] == "flat"){
            throw new CustomException("Invalid discount value", 303);
        }
        if(!isset($_FILES[$this->image_key])){
            throw new CustomException("Product images required", 303);
        }
        if(count($_FILES[$this->image_key]["name"]) < 1){
            throw new CustomException("You need to submit at least one Image", 303);
        }
        return $this->shop_type->validate_product_data();
    }

    public function assign_product_data()
    {
        $this->name = $this->data['name'];
        $this->description = $this->data['description'] ?? "";
        $this->price = $this->data['price'];
        $this->category = $this->data['category'];
        $this->quantity = $this->data['quantity'];
        $this->discount = $this->data['discount'];
        $this->discount_type = $this->data['discount_type'];
        $this->images = $_FILES[$this->image_key];
        return $this->shop_type->assign_product_data($this->product_id);
    }
    //generate product id with suffix prd
    public function generate_product_id()
    {
        return $this->id = "prd_".$this->shorten_shop_type_name()."_".substr(md5(uniqid(rand(), true)), 0, 6);
    }
    // function to shorten shop type name
    public function shorten_shop_type_name()
    {
        return substr($this->shop['shop_type'], 0, 3);
    }
    
    public function get_product_data()
    {
        return [
            "name" => $this->name,
            "product_id" => $this->product_id,
            "description" => $this->description,
            "price" => $this->price,
            "quantity" => $this->quantity,
            "category" => $this->category,
            "discount" => $this->discount,
            "discount_type" => $this->discount_type,
            "images" => $this->images,
            "created_by" => $this->user['unique_id'],
            "shop_id" => $this->shop['unique_id'],
            "shop_type" => $this->shop['shop_type']
        ];
    }
    public function create_product()
    {
        try{
            $this->validate_product_data();
            $this->assign_product_data();
            $product_data = $this->upload_images()->get_product_data();
            $shop_product_data = $this->shop_type->create_product($product_data);
            return $shop_product_data;
        }catch(CustomException $e){
            throw new CustomException($e->getMessage(), 500);
        }
    }

    protected function upload_images()
    {
        $uploaded_files = (new FileUploader($this->image_key, $this->image_path))
        ->set_reporting(false, false, false)
        ->add_ext('jpg', 'jpeg', 'png', 'gif')
        ->set_sizes(1000000, 20)
        ->set_prefix($this->product_id)
        ->move_files()
        ->get_uploads();
        $this->images = [];
        $count = 0;
        for($i = 1; $i <= 5; $i++){
            $this->images["image$i"] = $uploaded_files[$count]['name'];
            if(isset($uploaded_files[$count + 1]['name'])){
                $count++;
            }
        }
        return $this;
        


    }
    
    
    
    public static function get_all_products_from_shop($shop_id)
    {   
        $shop_type = Shops::get_shop_type($shop_id);
        $shop_id = Shops::resolve_id_for_db_2($shop_id);
        $shop_type_class = self::assign_shop_type_object($shop_type);
        $products = $shop_type_class::get_all_shop_product($shop_id);
        $products = self::ready_data_export($products);
        return $products;
    }
    public static function ready_data_export($data): array
    {
        foreach ($data as $key => $value) {
            # code...
            if(isset($data[$key]['images'])){
                $data[$key]['images'] = json_decode($value['images'], true);
                $data[$key]['created_by'] = (new Users($data[$key]['created_by']))->get_user_by_unique_id($data[$key]['created_by'])['username'];
                $data[$key]['sell_price'] = self::get_sell_price($data[$key]['price'], $data[$key]['discount'], $data[$key]['discount_type']);
                foreach($data[$key]['images'] as $key_2 => $value){
                    $data[$key]['images'][$key_2] = Helper::resolve_public_asset(Products::IMAGE_PATH.$value);
                }
            }
            if(isset($data['images']) && !isset($data[0])){
                $data['images'] = json_decode($data['images'], true);
                $data['created_by'] = (new Users($data['created_by']))->get_user_by_unique_id($data['created_by'])['username'];
                $data['sell_price'] = self::get_sell_price($data['price'], $data['discount'], $data['discount_type']);
                foreach($data['images'] as $key_2 => $value){
                    $data['images'][$key_2] = Helper::resolve_public_asset(Products::IMAGE_PATH.$value);
                }
                break;
            }
        }
        return $data;
    }
    public static function get_sell_price($price, $discount, $discount_type)
    {
        $price = (float)$price;
        $discount = (float)$discount;
        if($discount_type == "flat"){
            return $price - $discount;
        }else{
            return $price - ($price * ($discount / 100));
        }
    }
}