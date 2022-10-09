<?php
namespace Codad5\Wemall\Controller\V1;
use Codad5\Wemall\Controller\V1\ProductType\Clothing;
use Codad5\Wemall\Controller\V1\ProductType\ProductInterface;
use Codad5\Wemall\Helper\CustomException;
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
        var_dump($shop['shop_type']);
        if($shop_type instanceof ProductInterface){
            $this->shop_type = $shop_type;
        }
        else{
            throw new CustomException("Invalid Shop Type", 500);
        }
        // exit;
    }

    protected function assign_shop_type_object(string $type) : ProductInterface
    {
        // $shop_types = [
        //     "clothing" => function($shop, $user, $data){
        //         return new Clothing($shop, $user, $data);
        //     },
        //     // "food" => new Food($this->shop, $this->user, $this->data),
        //     // "automobile" => new Automobile($this->shop, $this->user, $this->data),
        //     // "phones" => new Phones($this->shop, $this->user, $this->data),
        //     // "furnitures" => new Furnitures($this->shop, $this->user, $this->data),
        // ];
        // $this->shop_types = $shop_types;
        // if(!array_key_exists($type, $this->shop_types)){
        //     throw new CustomException('Invalid Shop Type', 303);
        // }
        if(!in_array($type, self::$shop_type_array)){
            throw new CustomException("Invalid shop type", 404);
        }
        $shop_type_class = "Codad5\\Wemall\\Controller\V1\ProductType\\".ucfirst($type);
        return new $shop_type_class($this->shop, $this->user, $this->data) ?? null;
    }

    public function validate_product_data()
    {
        if(!isset($this->data['name']) && empty($this->data['name'])){
            throw new CustomException("Product name required", 303);
        }
        if(!isset($this->data['price']) && empty($this->data['price'])){
            throw new CustomException("Product price required", 303);
        }
        if(!isset($this->data['quantity']) && empty($this->data['quantity'])){
            throw new CustomException("Product quantity required", 303);
        }
        if(!isset($this->data['discount']) && empty($this->data['discount'])){
            throw new CustomException("Product discount required", 303);
        }
        if(!isset($this->data['category']) && empty($this->data['category'])){
            throw new CustomException("Product category type required", 303);
        }
        if(!isset($this->data['discount_type']) && empty($this->data['discount_type'])){
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
            $this->upload_images()->assign_product_data();
            $product_data = $this->get_product_data();
            $shop_product_data = $this->shop_type->create_product($product_data);
            return $shop_product_data;
        }catch(CustomException $e){
            throw new CustomException($e->getMessage(), 500);
        }
    }

    protected function upload_images()
    {
        $images = $_FILES[$this->image_key];
        $image_uploader = new FileUploader($this->image_key, $this->image_path);
        $uploaded_files = $image_uploader->set_reporting(false, false, false)
        ->add_ext('jpg', 'jpeg', 'png', 'gif')
        ->set_sizes(1000000, 20)
        ->set_prefix($this->product_id)
        ->move_files()
        ->get_uploads();
        $this->images = [];
        foreach($uploaded_files as $file){
            $this->images[] = $file['name'];
        }
        return $this;
        


    }
    
    public static function get_all_shop_product($shop_id)
    {
        $shop = Shops::get_details_by_id($shop_id);
        if(!$shop){
            throw new CustomException("Shop not found", 404);
        }
        $shop_type = $shop['shop_type'];
        if(!in_array($shop_type, self::$shop_type_array)){
            throw new CustomException("Invalid shop type", 404);
        }
        $shop_type_class = "Codad5\\Wemall\\Controller\V1\ProductType\\".ucfirst($shop_type);
        $products = $shop_type_class::get_all_shop_product($shop_id);
        return $products;

    }
    
}