<?php
namespace Codad5\Wemall\Controller\V1;
use Codad5\Wemall\Controller\V1\ProductType\Clothing;
use Codad5\Wemall\Controller\V1\ProductType\ProductInterface;
use Codad5\Wemall\Helper\CustomException;
use Codad5\Wemall\Helper\Helper;
use Codad5\Wemall\Helper\Validators;
use Codad5\Wemall\Model\Product;
use Codad5\FileHelper\FileUploader;
use Codad5\Wemall\Model\ProductType\ProductType;
use Codad5\Wemall\Model\Shop;
use Codad5\Wemall\Model\User;
use Trulyao\PhpRouter\HTTP\Request;

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
    CONST HTTP_IMAGE_NAME = 'images';
    protected string $category;
    protected string  $discount_type;
    protected array $discount_array = ["percentage", "flat"];
    protected array $images;
    protected string $image_key = "images";
    const IMAGE_PATH = "asset/images/products/";
    protected string $image_path = "images/products/";
    /**
     * Summary of create
     * @param Request $req
     * @return Product
     */
    public static function create(Request $req)
    {
        $shop = Shops::load($req->params('id'));
        Validators::validate_product_data($req, self::getProductClass($shop->shop_type)->getFieldSet());
        $newProduct = new Product();
        return $newProduct->create($shop, $req->body(), self::upload_images($shop->shop_type));
    }
    public static function edit(Request $req)
    {
        ['id' => $id, 'product_id' => $product_id] = $req->params();
        $product = Product::find($id);
        if(!$product) throw new CustomException('Product Dont exist', 400);
        Validators::validate_product_data($req, $product->externals::FIELD_SET);
        $shop = $product->withShop()->shop;
        if(!Shop::has_access($shop->unique_id, User::get_currenct_loggedin()->unique_id)) throw new CustomException('Access Denied', 304);
        return true;
    }
    public static function getProductClass($product_type) : ProductType
    {
        $type_as_string = Product::getProductTypeClass($product_type);
        return new $type_as_string;
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

    protected static function upload_images($prefix) : array
    {
         return (new FileUploader(self::HTTP_IMAGE_NAME, self::IMAGE_PATH))
        ->set_reporting(false, false, false)
        ->add_ext('jpg', 'jpeg', 'png', 'gif')
        ->set_sizes(1000000, 20)
        ->set_prefix($prefix)
        ->move_files()
        ->get_uploads();
        
    }
    
    
   
    
}