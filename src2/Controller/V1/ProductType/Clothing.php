<?php
namespace Codad5\Wemall\Controller\V1\ProductType;
use Codad5\Wemall\Helper\CustomException;
use Codad5\Wemall\Model\Product;

Class Clothing implements ProductInterface
{
    private string $id;
    private string $size;
    private string $color;
    private string $gender;
    private array $gender_array =  ['male', 'female', 'unisex'];
    private array $shop_data;
    private array $user_data;
    const TABLE = "clothing_products";
    protected static string $table = "clothing_products";
    private array $data;

    public function __construct(array $shop_data, array $user_data, array $data){
        $this->data = $data;
        $this->shop_data = $shop_data;
        $this->user_data = $user_data;
    }
    public function validate_product_data(): bool
    {
        
        if(!isset($this->data['color']) && empty($this->data['color'])){
            throw new CustomException("Product color required", 303);
        }
        if(!isset($this->data['size']) && empty($this->data['size'])){
            throw new CustomException("Product size required", 303);
        }
        // check gender
        if(!isset($this->data['gender']) && empty($this->data['gender'])){
            throw new CustomException("No Gender Selected", 500);
        }
        if(!in_array($this->data['gender'], $this->gender_array)){
            throw new CustomException("Gender Not Found", 500);
        }

        return true;

    }
    public function assign_product_data(String|int $unique_id) : Clothing
    {
        $this->id = $unique_id;
        $this->size = $this->data['size'];
        $this->color = $this->data['color'];
        $this->gender = $this->data['gender'];
        return $this ;
    }
    public function create_product(array $data, mixed ...$any): \PDOStatement
    {
        $product = new Product;
        $sql = "INSERT INTO ".self::TABLE." (product_id , size, color, gender) VALUES (?,?, ?,?)";
        $sql_bind_array = [
            $this->id,
            $this->size,
            $this->color,
            $this->gender
        ];
        return $product->create_product($sql, $data, $sql_bind_array);

    }
    public static function get_all_shop_product(String|int $shop_id) : array
    {
        return (new Product)->get_all_shop_product($shop_id, self::$table);
    }
    public static function get_product_by_id(String|int $product_id) : array
    {
        return (new Product)->get_product_by_product_id($product_id, self::$table);
    }

}
