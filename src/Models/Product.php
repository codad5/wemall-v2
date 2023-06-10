<?php

namespace Codad5\Wemall\Models;

use Codad5\FileHelper\FileUploader;
use Codad5\Wemall\Enums\AdminType;
use Codad5\Wemall\Enums\DiscountType;
use Codad5\Wemall\Enums\ProductStatus;
use Codad5\Wemall\Enums\ShopType;
use Codad5\Wemall\Libs\Database;
use Codad5\Wemall\Libs\Exceptions\AuthException;
use Codad5\Wemall\Libs\Exceptions\CustomException;
use Codad5\Wemall\Libs\Exceptions\ImageException;
use Codad5\Wemall\Libs\Exceptions\ProductException;
use Codad5\Wemall\Libs\Exceptions\ShopException;
use Codad5\Wemall\Libs\Utils\UserAuth;
use Codad5\Wemall\Models\{Shop};
class Product
{

    public string $product_id;
    public string $name;
    public Shop $shop;
    public string $description;
    public ShopType $type;
    public int $discount;
    public int $price;
    public DiscountType $discount_type;
    public int $quantity;
    public int $sold;
    public ProductStatus $status;
    public User $creator;
    public string $created_at;
    readonly Database $conn;
    protected bool $ready;
    readonly array $product_data;
    /**
     * @var ProductImage[]
     */
    public array $images;
    protected int $last_id;
    const TABLE = "products";

    public function __construct(string $id = null)
    {
        $this->conn = new Database(self::TABLE);
        $this->ready = false;
        if($id)
            $this->ready($id);
    }

    private function ready(string $id = null)
    {
        if(!$id) $id = $this->product_id;
        if ($this->ready) return $this;
        $product = $this->get_product_by('product_id', $id);
        if(!$product) throw new ProductException('Product not found', ProductException::PRODUCT_NOT_FOUND);
        $product = $product[0];
        $this->shop = Shop::find($product['shop_id']);
        $this->product_id = $product['product_id'];
        $this->name = $product['name'];
        $this->price = $product['price'];
        $this->description = $product['description'];
        $this->type = ShopType::tryFrom($product['type']);
        $this->discount = $product['discount'];
        $this->discount_type = DiscountType::tryFrom($product['discount_type']);
        $this->quantity = $product['quantity'];
        $this->sold = $product['sold'];
        $this->status = ProductStatus::tryFrom($product['status']);
        $this->created_at = $product['created_at'];
        $this->creator = User::find($product['creator_id']);
        $this->ready = true;
        $this->images = ProductImage::getImaagesFromProduct($this->product_id, false);
        $this->getProductData();
        return $this;
    }

    public function get_product_by($by, $value)
    {
        $data = $this->conn->where($by, $value);
        return $data && count($data) > 0 ? $data : null;
    }

    static function create(Shop $shop, $field): Product
    {
        $self = new self;
        $product_id = $self->generate_id();
        $creator = UserAuth::who_is_loggedin() ?? throw new AuthException("You need to be logged into perform this operation");
        $sql = "INSERT INTO ".self::TABLE." (product_id, shop_id, name, price, description, type, discount, discount_type, quantity, creator_id) VALUES (:product_id, :shop_id, :name, :price, :description, :type, :discount, :discount_type, :quantity, :creator_id); ".$shop->type->getProductInsertSqlQuery();
        $new_binding = [];
        foreach ($field as $index => $item) {
            if(!str_contains($sql, ":$index")) continue;
            $new_binding[":$index"] = $item;
        }
        $bindings = [
            ...$new_binding,
            ':creator_id' => $creator->user_id,
            ':shop_id' => $shop->shop_id,
            ':product_id' => $product_id,
            ':type' => $shop->type->value
        ];
        $data = Database::query($sql, $bindings);
        $test = $sql;
        foreach ($bindings as $index => $binding) {
            $test = str_replace($index, "'".trim($binding)."'", $test);
        }
        try{
            echo "running";
            return (new self($product_id))->uploadPhotos();
        }
        catch (\Exception $e){
            (new self($product_id))->delete();
            throw new ProductException("Something Went Wrong Adding you as Admin");
        }
    }

    function update(Shop $shop, array $fields)
    {
        $sub_query = "";
        $product_fields = $this->type->getFields();
        foreach ($product_fields as $key => $field)
        {
            $sub_query.= "sub.$field = :$field";
            if (count($product_fields) > $key+1) $sub_query.=", ";
        }
        $sql = "UPDATE ".self::TABLE." AS main JOIN {$this->type->getProductTableName()} AS sub ON main.product_id =  sub.product_id 
        SET main.name = :name,
            main.price = :price,
            main.description = :description,
            main.discount = :discount,
            main.discount_type = :discount_type,
            main.quantity = :quantity,
            $sub_query
        WHERE main.product_id = :product_id";
        $editable_fields = ["name", "price", "description", "discount", "discount_type", "quantity", ...$product_fields];
        $new_binding = [];
        foreach ($fields as $index => $item) {
            if(!str_contains($sql, ":$index")) continue;
            $new_binding[":$index"] = $item;
        }
        $bindings = [
            ...$new_binding,
            ":product_id" => $this->product_id
        ];
//        echo  "<pre>";
        $data = Database::query($sql, $bindings);
        $test = $sql;
        foreach ($bindings as $index => $binding) {
            $test = str_replace("$index", "'".trim($binding)."'", $test);
        }
//        var_dump($test, $bindings);
//        exit();
        return $this;
    }

    function remove()
    {
    }
    function delete(): static
    {
        $sql = "UPDATE ".self::TABLE." SET status = :status WHERE product_id = :product_id";
        $data = Database::query($sql, [":product_id" => $this->product_id, ":status" => ProductStatus::deleted->value]);
        return $this->ready();
    }

    protected function generate_id() : string
    {
        return strtoupper(substr('P'.$this->last_id()."A".md5(uniqid(rand(), true)), 0, 10));
    }

    protected function last_id() : int{
        if (isset($this->last_id))
            return $this->last_id;
        $data = $this->all();
        return $this->last_id = count($data) > 0 ? $data[0]['id'] : 0;
    }

    public static function all(?ShopType $shopType = null): null|array
    {
        $query = "SELECT * FROM " . self::TABLE;
        if ($shopType) $query .= "INNER JOIN {$shopType->getProductTableName()} ON ".self::TABLE.".product_id = {$shopType->getProductTableName()}.product_id";
        return Database::query($query)?->fetchAll();
    }

    /**
     * @throws ImageException
     * @throws CustomException
     */
    private function uploadPhotos(): static
    {
        ProductImage::uploadPhotos($this->product_id, $this->shop);
        $this->images = ProductImage::getImaagesFromProduct($this->product_id);
        return $this;
    }


    /**
     * @throws ProductException
     * @throws CustomException
     */
    static function getProductFromShop(Shop $shop, string $product_id = null, ProductStatus $status = ProductStatus::active): false|array
    {
        $query = "SELECT main.*, sub.*, users.username AS creator FROM " .self::TABLE ." AS main INNER JOIN {$shop->type->getProductTableName()} AS sub ON main.product_id = sub.product_id INNER JOIN users ON main.creator_id = users.user_id wHERE main.shop_id = ? AND main.status = ?";
        $binding = [$shop->shop_id, $status->value];
        if($product_id) {
            $query .= "AND main.product_id = ?";
            $binding[] = $product_id;
        }
        $products = Database::query($query, $binding)->fetchAll();
        if(!$products) return [];
        foreach ($products as $index => $product) {
            $products[$index] = [...$product, "sell_price" => self::calculateSellPrice($product['discount_type'], $product['price'], $product['discount'])];
        }
        return $products;
    }

    /**
     * @throws ProductException
     */
    static function calculateSellPrice(string $discount_method, int $price, int $discount): float|int
    {
        $discountType = DiscountType::tryFrom($discount_method);
        return $discountType->getSellPrice($price, $discount);
    }

    /**
     * @throws ProductException
     */
    public static function find(mixed $product_id): ?Product
    {
        try{
            return new self($product_id);
        }catch (ProductException $e)
        {
            if($e->getCode() == ProductException::PRODUCT_NOT_FOUND) return null;
            throw $e;
        }
    }
    function getProductData()
    {
        if(isset($this->product_data)) return $this->product_data;
        return $this->product_data = Database::query("SELECT * FROM {$this->type->getProductTableName()} WHERE product_id = ?", [$this->product_id])->fetch();
    }

    /**
     * @throws ProductException
     * @throws CustomException
     */
    function toArray(): array
    {

        return [
            'product_id' => $this->product_id,
            'shop_id' => $this->shop->shop_id,
            'name' => $this->name,
            'price' => $this->price,
            'description' => $this->description,
            'type' => $this->type->value,
            'discount' => $this->discount,
            'discount_type' => $this->discount_type->value,
            'quantity' => $this->quantity,
            'sold'=>$this->sold,
            'status' => $this->status->value,
            'creator_id' => $this->creator->user_id,
            'created_at' => $this->created_at,
            'creator' => $this->creator->username,
            ...$this->getProductData()

        ];
    }

}