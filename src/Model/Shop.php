<?php
namespace Codad5\Wemall\Model;
use Codad5\Wemall\DS\lists;
use Codad5\Wemall\Handlers\CustomException;
use Codad5\Wemall\Configs\Db;
use Codad5\Wemall\Model\Admins;

class Shop{
    const SHOP_TYPE_ARRAY = ['Clothing'];
    public $name;
    public $email;
    public $password;
    public $confirm_password;
    public $id;
    public $description;
    public $unique_id;
    public $api_key;
    public $shop_type;
    public $created_at;
    public $updated_at;
    public $deleted_at;
    public $form;
    protected Lists $products;
    protected User $created_by;
    public $table = 'shops';
    
    protected Db $conn;
    protected Admins $admins;
    protected int $last_id;
    protected array $data_array = [];

    public function __construct($id = null)
    {
        $this->conn = new Db();
        if ($id)
            $this->ready($id);
    }

    protected function ready($id)
    {
        $data = $this->get_by('id', $id) ?? $this->get_by('unique_id', $id);
        if(!$data) return $this;

        $data = $data[0];
        $this->data_array = $data;
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->description = $data['description'];
        $this->email = $data['email'];
        $this->unique_id = $data['unique_id'];
        $this->api_key = $data['api_key'];
        $this->shop_type = $data['shop_type'];
        $this->created_at = $data['created_at'];
        return $this;
    }
    public function withAdmins() : self
    {
        $this->admins = $this->admins ??  new Admins($this);
        return $this;
    }
    public function withOwner() : self
    {
        $this->created_by = $this->created_by ?? User::find($this->data_array['created_by']);
        return $this;
    }
    /**
     * To get al shop product --in use
     * @return Shop
     */
    public function withProducts()
    {
        $this->products = $this->products ?? Product::where('shop_id', $this->unique_id);
        $this->products->map(fn(Product $product) => $product->withCreator());
        return $this;
    }
    public function getProducts() : Lists
    {
        return $this->withProducts()->products;
    }
    public function findProduct($id) 
    {
        $product = Product::find($id);
        if($product->shop_id !== $this->unique_id) return null;
        return $product;
    }
    public function getOwners() : User
    {
        return $this->withOwner()->created_by;
    }
    public function getAdmins(): Admins
    {
        return $this->withAdmins()->admins;
    }
    protected function last_id() : int
    {
        if (isset($this->last_id))
            return $this->last_id;
        $sql = "SELECT * FROM $this->table";
        $data = $this->conn->select_data($sql, [
            
        ]);
        return $this->last_id = count($data) > 0 ? $data[0]['id'] : 0;
    }
    protected function generate_id()
    {
        return 'shop_'.$this->last_id().substr(md5(uniqid(rand(), true)), 0, 12);
    }

    protected function generate_api_key()
    {
        return 'shK_'.$this->last_id().substr(md5(uniqid(rand(), true)), 0, 12);
    }
    public function create(User $user)
    {
        if (self::where('email', $this->email)->first())
            throw new CustomException("email : ($this->email) already in use", 300);
        $this->unique_id = $this->generate_id();
        $this->api_key = $this->generate_api_key();
        $sql = "INSERT INTO $this->table (name, description,  created_by, email, unique_id, api_key, shop_type) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $this->conn->query_data($sql, [
            $this->name,
            $this->description,
            $user->unique_id,
            $this->email,
            $this->unique_id,
            $this->api_key,
            $this->shop_type
        ]);
        
        return $this->ready(self::find($this->unique_id)->id)->withAdmins()->add_admin($user);

    }
    public function add_admin(User $user)
    {
        $this->withAdmins()->admins->add($user, 3) ;
        return $this;
    }

    public function getShopTypeModel()
    {
        if(!in_array($this->shop_type, self::SHOP_TYPE_ARRAY)){
            throw new CustomException("Invalid shop type", 404);
        }
        return "Codad5\\Wemall\\Model\\ProductType\\".ucfirst($this->shop_type);
    }
    public function get_by(string $by, $value) : array|null
    {
        $sql = "SELECT * FROM $this->table WHERE $by = ?";
        $data = $this->conn->select_data($sql, [
            $value
        ]);
        
        return count($data) > 0 ? $data : null;
    }
    
    //get all shops by a specific user
    public function get_shops_where_admin_is(string $by) : array
    {
        $data = $this->get_all_shops();
        $return_data = [];
        foreach($data as $shop){
            $admins = json_decode($shop['admins']);
            if(in_array($by, $admins->all)){
                $return_data[] = $shop;
            }
        }
        return $return_data;
    }
    public function get_all_shops() : array|null
    {
        $sql = "SELECT * FROM $this->table;";
        return $this->conn->select_data($sql, [
            
        ]);
       
    }

    public function delete()
    {
        $sql = "DELETE FROM $this->table WHERE id = ?";
        $this->conn->query_data($sql, [
            $this->id
        ]);

        return $this->withAdmins()->admins->delete();

    }
    public static function find($id)
    {   
        $data = (new Shop)->get_by('id', $id);
        if($data) return new Shop($id);
        $data = (new Shop)->get_by('unique_id', $id);
        if($data) return new Shop($id);
        return $data;
    }
    public static function where($column, $value)
    {
        return (new lists((new Shop)->get_by($column, $value)))->map(function ($data) {
            return new Shop($data['id']);
        });
    
    }
    
    public static function get_admin($shop_id)
    {
        return admins::list($shop_id);
    }
    public static function has_access($shop_id, $user_id, $level = null)
    {
        return self::get_admin($shop_id)->map(function ($data) use($user_id, $level) {
            return $user_id == isset($data['user_id']) && $level ? $level == $data['level'] : true;
        })->count() > 0;
    }
    /**
     * Summary of toArray --in use
     * @return array
     */
    public function toArray()
    {
        $created_by = isset($this->created_by) ? $this->created_by->toArray() : [];
        $admins = isset($this->admins) ? $this->admins->toArray() : [];
        $products = isset($this->products) 
                    ? $this->products
                    ->map(function (Product $product) {
                            return $product?->toArray();
                        })?->to_array() : [];
        return [
            'name' => $this->name,
            'description' => $this->description,
            'created_by' => $created_by,
            'email' => $this->email,
            'unique_id' => $this->unique_id,
            'api_key' => $this->api_key,
            'shop_type' => $this->shop_type,
            'created_at' => $this->created_at,
            'form' => $this->form ?? '',
            'admins' => $admins,
            'products' => $products
        ];
    }

}