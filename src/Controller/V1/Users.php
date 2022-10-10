<?php
namespace Codad5\Wemall\Controller\V1;

use Codad5\Wemall\Model\User as User;   
use Codad5\Wemall\Helper\CustomException as CustomException;
 use \Codad5\Wemall\Helper\Validators as Validator;
 use Codad5\Wemall\Model\Shop;

class Users
{
    private User $user;
    protected string|null $name;
    protected string $username;
    protected string|null $email;
    protected string|null $password;
    protected string $id;
    protected string $unique_id;
    protected string $created_at;
    protected string $updated_at;
    public string $login;
    protected string $api_key;
    
    
    public function __construct(
        string $user_name,
        string $password = null,
        string $email = null,
        string $name = null
    )
    {
        $this->login = strtolower($user_name);
        $this->name = $name;
        $this->username = strtolower($user_name);
        $this->email = $email;
        $this->password = $password;
        $this->unique_id = $this->generate_user_id();
        $this->api_key = $this->generate_api_key();
        $this->created_at = $this->generate_timestamp();
        $this->user = new User();
    }

    #generate_timestamp
    private function generate_timestamp()
    {
        return date('Y-m-d H:i:s');
    }

    #function to generate user unique id of 8 characters
    public function generate_user_id()
    {
        return "uuid_".substr(md5(uniqid(rand(), true)), 0, 8);
    }
    public function get_user_id()
    {
        return $this->unique_id;
    }
    public static function get_user_unique_id($user){
        $conn = (new User);
        $user = Validator::validate_email($user) ?
             $conn->get_user_by("email", $user) :
             $conn->get_user_by("username", $user);
        return $user ? $user[0]['unique_id'] : false;
    }
    // generate user unique api key of 32 characters
    public function generate_api_key()
    {
        return "uk_".substr(md5(uniqid(rand(), true)), 0, 32);
    }
    

    #validate all user data
    public function validate_login_user_data(bool $require_password = true)
    {
        if (empty($this->login)) {
            throw new CustomException("Username or Email is required", 400);
        }
        if (empty($this->password) && $require_password) {
            throw new CustomException("Password is required", 400);
        }
        if (strlen($this->password) < 8) {
            throw new CustomException("Password must be at least 8 characters", 400);
        }
        //add validation to check if email already exists
        if (!$this->get_user_by_email($this->login) && Validator::validate_email($this->login)) {
            throw new CustomException("Email does not exists", 400);
        }
        //add validation to check if username already exists
        if (!$this->get_user_by_username($this->login) && !Validator::validate_email($this->login)) {
            throw new CustomException("Username does not exists >>".Validator::validate_email($this->login), 400);
        }
        return true;
    }
    public function validate_signup_user_data()
    {
        if (empty($this->name)) {
            throw new CustomException("Name is required", 400);
        }
        if (empty($this->username)) {
            throw new CustomException("username is required", 400);
        }
        if (empty($this->email)) {
            throw new CustomException("Email is required", 400);
        }
        if (empty($this->password)) {
            throw new CustomException("Password is required", 400);
        }
        if(!Validator::validate_password($this->password)){
            throw new CustomException("Invalid password format", 400);
        }
        if (!Validator::validate_email($this->email)) {
            throw new CustomException("Invalid email format", 400);
        }
        if (strlen($this->password) < 8) {
            throw new CustomException("Password must be at least 8 characters", 400);
        }
        //add validation to check if email already exists
        if (!Validator::validate_email($this->email)) {
            throw new CustomException("Invalid Email", 400);
        }
        if ($this->get_user_by_email($this->email)) {
            throw new CustomException("Email already exists", 400);
        }
        //add validation to check if username already exists
        if ($this->get_user_by_username($this->username)) {
            throw new CustomException("Username already exists", 400);
        }

    }

    public function create_user()
    {
        try {
            $this->validate_signup_user_data();
            return $this->user->save(
                [
                    'name' => $this->name,
                    'username' => $this->username,
                    'email' => $this->email,
                    'password' => password_hash($this->password, PASSWORD_DEFAULT),
                    'unique_id' => $this->unique_id,
                    "api_key" => $this->api_key,
                ]
            );
            
            
        } catch (CustomException $e) {
            throw new CustomException($e->getMessage(), $e->getCode());
        }
    }

    public function get_user_by_id($id)
    {
        try {
            $this->user->id = $id;
            return $this->user->get_user_by("id", $id);
        } catch (\Throwable $th) {
            throw new CustomException($th->getMessage(), 500);
        }
    }
    public function get_user_by_unique_id($unique_id)
    {
        try {
            $this->user->unique_id = $unique_id;
            return $this->user->get_user_by("unique_id", $unique_id)[0];
        } catch (\Throwable $th) {
            throw new CustomException($th->getMessage(), 500);
        }
    }

    #get user by email
    public function get_user_by_email($email)
    {
        try {
            $this->user->email = $email;
            return $this->user->get_user_by("email", $email);
        } catch (\Throwable $th) {
            throw new CustomException($th->getMessage(), 500);
        }
    }

    #get user by username
    public function get_user_by_username($username)
    {
        try {
            
            return $this->user->get_user_by("username", $username);
        } catch (\Throwable $th) {
            throw new CustomException($th->getMessage(), 500);
        }
    }
    public function get_user_by_name($name)
    {
        try {
            
            return $this->user->get_user_by("name", $name);
        } catch (\Throwable $th) {
            throw new CustomException($th->getMessage(), 500);
        }
    }

    public function get_all_users()
    {
        try {
            return $this->user->get_all_users();
        } catch (\Throwable $th) {
            throw new CustomException($th->getMessage(), 500);
        }
    }

    public function update_user($data)
    {
        try {
            $this->user->id = $data['id'];
            $this->user->name = $data['name'];
            $this->user->email = $data['email'];
            $this->user->password = $data['password'];
            $this->user->confirm_password = $data['confirm_password'];
            $this->user->update_user();
            return $this->user;
        } catch (\Throwable $th) {
            throw new CustomException($th->getMessage(), 500);
        }
    }

    #user login
    public function login()
    {
        try {
            $this->validate_login_user_data();
            $user_data = Validator::validate_email($this->login) ? $this->get_user_by_email($this->login) : $this->get_user_by_username($this->login);
            if(!$user_data) {
                throw new CustomException("User Not Found", 200);
            }
            $user_data = $user_data[0];
            if(!password_verify($this->password, $user_data['password'])){
                throw new CustomException("Incorrect Credentials", 200);
            }
            return $user_data;
        } catch (\Throwable $th) {
            throw new CustomException($th->getMessage(), 200);
        }
    }
        public function set_login_session($data){
            session_start();
            // check user exists
            if(!$this->user->get_user_by("id", $data['id'])){
                throw new CustomException("User Not Found", 200);
            }
            $_SESSION['username'] = $data['username'];
            $_SESSION['name'] = $data['name'];
            $_SESSION['email'] = $data['email'];
            $_SESSION['user_id'] = $data['id'];
            $_SESSION['user_unique'] = $data['unique_id'];
        
    }
    
    public static function get_all_shops_by($id) : array
    {
        try {
            $shop = new Shop();
            $users_shops = $shop->get_shops_where_admin_is($id);
            foreach ($users_shops as $key => $value) {
                $users_shops[$key] = Shops::prepare_shop_data($value);
            }
            return $users_shops;

            
        } catch (\Throwable $th) {
            throw new CustomException($th->getMessage(), 500);
        }
    }
    

    public static function any_is_logged_in(){
        return isset($_SESSION['username']) && 
        isset($_SESSION['email']) &&
        isset($_SESSION['name']) &&
        isset($_SESSION['user_id']) &&
        isset($_SESSION['user_unique']) &&
        (new User)->get_user_by("id", $_SESSION['user_id']);
    }

    
}