<?php
namespace Codad5\Wemall\Controller\V1;

use Codad5\Wemall\Model\Shop as Shop;
use Codad5\Wemall\Helper\CustomException as CustomException;
use Codad5\Wemall\Helper\Validators;

class Shops
{
    private Shop $shop;
    protected string $id;
    protected string|null $name;
    protected string|null $email;
    protected string|null $description;
    protected string $api_key;
    protected Users $created_by;

   public function __construct($name = null, $description = null, $email = null, Users $created_by = null)
   {
    $this->shop = new Shop;
    $this->name = $name;
    $this->email = $email;
    $this->description = $description;
    $this->created_by = $created_by ? $created_by : new Users('________________');
    $this->id = $this->generate_id();
    $this->api_key = $this->generate_api_key();
   }

   public function validate_shop_data()
   {
    if(empty($this->name)){
        return new CustomException("shop name required", 303);
    }
    if(empty($this->description)){
        return new CustomException("shop description required", 303);
    }
    if(empty($this->id)){
        return new CustomException("Server Error", 303);
    }
    //validate email
    if(!Validators::validate_email($this->email)){
        return new CustomException("Invalid email", 303);
    }
    //check if shop email already exists
    if(!$this->shop->get_shop_by("email", $this->email)){
        return new CustomException("Email already exists", 303);
    }
    //validate user
    if(!$this->created_by->validate_login_user_data(false)){
        return new CustomException("Error Validating User", 303);
    }
    //  added validation for character free name and description to prevent cross site scripting

   }
   public static function get_details_by_id($id)
   {
    $id = "shid_".$id;
    $shop = (new shop)->get_shop_by('unique_id', $id);
    return $shop ? $shop[0] : false;
   }
   public static function get_details_by_email($email)
   {
    $shop = (new shop)->get_shop_by('email', $email);
    return $shop ? $shop[0] : false;
   }
   public static function export_id($id)
   {
    return "shid_".$id;
   }
    public static function import_id($id)
    {
     return str_replace("shid_", "", $id);
    }
    public static function resolve_id($id)
    {
     if (strpos($id, 'shid_') !== false) {
        return self::import_id($id);
        }else{
        return self::export_id($id);
        }
    }
    public static function resolve_id_for_db($id)
    {
     if (strpos($id, 'shid_') !== false) {
        return self::import_id($id);
        }else{
        return $id;
        }
    }
    public function get_id()
    {
     return $this->id;
    }
   public function create_shop()
   {
        try{
            $this->validate_shop_data();
            $user_id = $this->created_by->get_user_unique_id($this->created_by->login);
            if(!$user_id){
                throw new CustomException("Invalid User data", 300);
            }
            $admins = json_encode([
                "first" => [
                    $user_id
                ],
                "second" => [],
                "third" => [],
                "all" => [
                    $user_id
                ]
            ]);
            return $this->shop->save([
                "name" => $this->name,
                "description" => $this->description,
                "created_by" => $user_id,
                "email" => $this->email,
                "unique_id" => $this->id,
                "api_key" => $this->api_key,
                "admins" => $admins

            ]);

        }catch(CustomException $e){
            throw new CustomException($e->getMessage(), $e->getCode());
        }
   }
   public static function shop_exists($id)
   {
    $id = self::resolve_id_for_db($id);
    return self::get_details_by_id($id);
   }
   //check number of total shops
    public static function no_of_total_shops()
    {
        return count((new Shop)->get_all_shops());
    }
   protected function generate_id() : string
   {
    $no_of_shops = self::no_of_total_shops();
    $no_of_shops++;
    return "shid_$no_of_shops".substr(md5(uniqid(rand(), true)), 0, 6);
   }

   protected function generate_api_key() : string
   {
    return "shk_".substr(md5(uniqid(rand(), true)), 0, 32);
   }

   public function get_admins($id = null)
   {
    $id = $id ? $id : $this->id;
    $admins = $this->get_details_by_id($id)['admins'];
    return json_decode($admins, true);
   }
   public static function get_admins_by_level($level, $shop_id = null)
   {
    $shop_id = $shop_id ? $shop_id : self::$id;
    $admins = (new Shops)->get_admins($shop_id);
    return $admins[$level];
   }

   public static function is_shop_admin($shop_id, $user_id)
   {
    $admins = (new Shops)->get_admins($shop_id);
    $all_admins = $admins['all'];
    return in_array($user_id, $all_admins);
   }
    public static function is_shop_first_admin($shop_id, $user_id)
    {
     $admins = (new Shops)->get_admins($shop_id);
     $first_admins = $admins['first'];
     return in_array($user_id, $first_admins);
    }
    public static function is_shop_second_admin($shop_id, $user_id)
    {
     $admins = (new Shops)->get_admins($shop_id);
     $second_admins = $admins['second'];
     return in_array($user_id, $second_admins);
    }
   
   public static function delete_shop($id, $user_id) {
    $id = self::resolve_id_for_db($id);
    $shop = self::get_details_by_id($id);
    if(!$shop){
        return new CustomException("Shop not found", 303);
    }
    if(!self::is_shop_first_admin($id, $user_id)){
        return new CustomException("You are not an admin of this shop", 303);
    }
    $shop = new Shop;
    $shop->delete_shop(self::resolve_id($id));
    return true;
   }
    public static function update_shop($id, $user_id, $data) {
     $id = self::resolve_id_for_db($id);
     $shop = self::get_details_by_id($id);
     if(!$shop){
          return new CustomException("Shop not found", 303);
     }
     if(!self::is_shop_admin($id, $user_id)){
          return new CustomException("You are not an admin of this shop", 303);
     }
     $shop = new Shop;
     $shop->update_shop(self::resolve_id($id), $data);
     return true;
    }

   
}
