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
use  \Codad5\Wemall\Models\{Shop};
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

    private function ready(string $id)
    {
        if(!$id) $id = $this->product_id;
        if ($this->ready) return $this;
        $product = $this->get_product_by('product_id', $id);
        if(!$product) throw new ProductException('Product not found');
        $product = $product[0];
        $this->product_id = $product['product_id'];
        $this->shop = Shop::find($product['shop_id']);
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
        return $this;
    }

    public function get_product_by($by, $value)
    {
        $data = $this->conn->where($by, $value);
        return $data && count($data) > 0 ? $data : null;
    }

    static function create(Shop $shop, $field)
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

    public static function all(?ShopType $shopType = null): false|array
    {
        $query = "SELECT * FROM " . self::TABLE;
        if ($shopType) $query .= "INNER JOIN {$shopType->getProductTableName()} ON ".self::TABLE.".product_id = {$shopType->getProductTableName()}.product_id";
        return Database::query($query)->fetchAll();
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

    function delete()
    {
    }

    static function getProductFromShop(Shop $shop)
    {
        $query = "SELECT * FROM " . self::TABLE ." INNER JOIN {$shop->type->getProductTableName()} ON ".self::TABLE.".product_id = {$shop->type->getProductTableName()}.product_id wHERE ".self::TABLE.".shop_id = ?";
        return Database::query($query, [$shop->shop_id])->fetchAll();
    }


}