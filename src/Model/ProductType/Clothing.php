<?php
namespace Codad5\Wemall\Model\ProductType;
use Codad5\Wemall\Helper\CustomException;
use Codad5\Wemall\Configs\Db;

class CLothing implements ProductType{
    
    use ProductTraits;
    /**
     */
    protected Db $conn;
    CONST TABLE = 'clothing_products';
    const FIELD_SET = ['size', 'color', 'gender'];
    public $product_id;
    public $size;
    public $color;
    public $gender;
    public array $data_array;
    public function __construct($id = null)
    {
        $this->conn = new Db();
        if ($id)
            $this->ready($id);
    }

    protected function ready($id){
        $data = $this->get_by('product_id', $id) ?? $this->get_by('id', $id);
        if(!$data) return $this;

        $data = $data[0];
        $this->data_array = $data;
        $this->product_id = $data['product_id'];
        $this->size = $data['size'];
        $this->color = $data['color'];
        $this->gender = $data['gender'];
        return $this;
        
    }
    public static function use (array $data = null) : ProductType
    {
        
        if(empty($data)){
            
        }
        $data_match = self::does_not_match_data_field($data);
        if($data_match)  throw new CustomException("Invalid data type in for a clothing product, $data_match is missing", 500);
        $self = new self();
        $self->data_array = $data;
        $self->product_id = $data['product_id'];
        $self->size = $data['size'];
        $self->color = $data['color'];
        $self->gender = $data['gender'];
        return $self;
    }
    protected function merge()
    {
        $this->data_array = array_merge($this->data_array, [
            'size' => $this->size,
            'color' => $this->color,
            'gender' => $this->gender
        ]);
        return $this;
    }
    public function toArray() : array
    {
        return $this->merge()->data_array;
    }
    public static function does_not_match_data_field(array $data = [])
    {
        foreach(self::FIELD_SET as $key)
        {
            if(!isset($data[$key])) return $key;
        }
        return false;
    }
    public function get_by(string $by, $value) : array|null
    {
        $sql = "SELECT * FROM ".self::TABLE." WHERE $by = ?";
        $data = $this->conn->select_data($sql, [
            $value
        ]);
        
        return count($data) > 0 ? $data : null;
    }
    public function new($unique_id, $data) : bool
    {
        // var_dump($data);
        $sql = "INSERT INTO ".self::TABLE." (product_id, size, color, gender) VALUES (?,?,?,?);";
        $this->conn->query_data($sql,[
            $unique_id,
            $data['size'],
            $data['color'],
            $data['gender']
        ]);
        return true;
    }
    public function delete($product_id =  null) : bool
    {
        $sql = "DELETE FROM ".self::TABLE." WHERE product_id = ?";
        $this->conn->query_data($sql, [
            $product_id ?? $this->product_id
        ]);
        return true;
    }
    public static function getFieldSet() : array
    {
        return self::FIELD_SET;
    }
}