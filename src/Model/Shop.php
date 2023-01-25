<?php
namespace Codad5\Wemall\Model;
use Codad5\Wemall\DS\lists;
use Codad5\Wemall\Libs\CustomException;
use Codad5\Wemall\Libs\Database;

class Shop{
    const SHOP_TYPE_ARRAY = ['Clothing'];
    public string $name;
    public string $email;
    public string $password;
    public string $confirm_password;
    public int|string $id;
    public string $description;
    public string $unique_id;
    public string $api_key;
    public string $shop_type;
    public string $created_at;
    public string $updated_at;
    public string $deleted_at;
    public $form;
    protected Lists $products;
    protected User $created_by;
    protected const TABLE = 'SHOPS';
    
    protected Database $conn;
    protected Admins $admins;
    protected int $last_id;
    protected array $data_array = [];

    /**
     * @throws CustomException
     */
    public function __construct($id = null)
    {
        $this->conn = new Database(self::TABLE);
        if ($id)
            $this->ready($id);
    }

    /**
     * @throws CustomException
     */
    protected function ready($id): static
    {
        if(!$id) return $this;
        $data = $this->get_by('id', $id) ?? $this->get_by('unique_id', $id);
        if(!$data) throw new CustomException('Shop Not available');

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
        $this->admins = $this->admins ?? Admins::list($this);
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
    public function withProducts(): self
    {
        $this->products = $this->products ?? Product::where('shop_id', $this->unique_id);
        $this->products->map(fn(Product $product) => $product->withCreator());
        return $this;
    }
    public function getProducts() : Lists
    {
        return $this->withProducts()->products;
    }
    public function findProduct($id): ?Product
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
        $data = $this->conn->all();
        return $this->last_id = count($data) > 0 ? $data[0]['id'] : 0;
    }
    protected function generate_id(): string
    {
        return $this->last_id().substr(md5(uniqid(rand(), true)), 0, 12);
    }

    protected function generate_api_key(): string
    {
        return 'shK_'.$this->last_id().substr(md5(uniqid(rand(), true)), 0, 12);
    }

    /**
     * @throws CustomException
     */
    public function create(User $user): Shop
    {
        try{
            if (self::where('email', $this->email)->first())
                throw new CustomException("email : ($this->email) already in use", 300);
            $this->unique_id = $this->generate_id();
            $this->api_key = $this->generate_api_key();
//        $sql = "INSERT INTO ".self::TABLE."(name, description, created_by, email, unique_id, api_key, shop_type) VALUES (?, ?, ?, ?, ?, ?, ?);";
            $sql = "INSERT INTO " . self::TABLE . " (`name`, `description`, `created_by`, `email`, `unique_id`, `api_key`, `shop_type`) VALUES (?,?,?,?,?,?,?)";
            $main = $this->conn->query($sql, [
                $this->name,
                $this->description,
                $user->unique_id,
                $this->email,
                $this->unique_id,
                $this->api_key,
                $this->shop_type
            ]);
            $shop = self::find($this->unique_id);
            if (!$shop) throw new CustomException('User not Created, try again later');
            return $shop->withAdmins()->add_admin($user);
        }catch (\Exception $e)
        {
            $this->ready($this->unique_id)->delete();
            echo "james";
            throw $e;
        }

    }
    public function add_admin(User $user): static
    {
        $this->withAdmins()->admins->add($this, $user, 3) ;
        return $this;
    }

    /**
     * @throws CustomException
     */
    public function getShopTypeModel(): string
    {
        if(!in_array($this->shop_type, self::SHOP_TYPE_ARRAY)){
            throw new CustomException("Invalid shop type", 404);
        }
        return "Codad5\\Wemall\\Model\\ProductType\\".ucfirst($this->shop_type);
    }

    /**
     * @throws CustomException
     */
    public function get_by(string $by, $value) : array|null
    {
        $data = $this->conn->where($by, $value);
        return count($data) > 0 ? $data : null;
    }

    public function get_all_shops() : array|null
    {
        $sql = "SELECT * FROM $this->table;";
        return $this->conn->select($sql, [

        ]);
       
    }

    /**
     * @throws CustomException
     */
    public function delete(): ?\PDOStatement
    {
        $sql = "DELETE FROM ".self::TABLE." WHERE id = ?";
        $this->conn->query($sql, [
            $this->id
        ]);

        return $this->withAdmins()->admins->delete();

    }
    public static function find($id): array|Shop|null
    {
        $data = (new Shop)->get_by('id', $id);
        if($data) return new Shop($id);
        $data = (new Shop)->get_by('unique_id', $id);
        if($data) return new Shop($id);
        return $data;
    }

    /**
     * @throws CustomException
     */
    public static function where($column, $value): lists
    {
        return (new lists((new Shop)->get_by($column, $value)))->map(function ($data) {
            return new Shop($data['id']);
        });
    
    }
    
    public static function get_admin($shop_id): lists
    {
        return admins::list($shop_id);
    }
    public static function has_access($shop_id, $user_id, $level = null): bool
    {
//        var_dump(self::get_admin($shop_id));
        $ou = self::get_admin($shop_id)->filter(function (Admins $admin) use($user_id, $level) {
            if (isset($level) && $level != $admin->level) return false;
//            if($user_id == $admin->user_id)

    })->count() > 0;
        exit();
        return $ou;
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