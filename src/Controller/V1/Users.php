<?php
namespace Codad5\Wemall\Controller\V1;

use Codad5\Wemall\Model\User as User;   
use Codad5\Wemall\Helper\CustomException as CustomException;
 use \Codad5\Wemall\Helper\Validators as Validator;

class Users
{
    private User $user;
    protected string|null $name;
    protected string $username;
    protected string|null $email;
    protected string $password;
    protected string $id;
    protected string $created_at;
    protected string $updated_at;
    protected string $login;
    
    
    public function __construct(
        string $user_name,
        string $password,
        string $email = null,
        string $name = null
    )
    {
        $this->login = strtolower($user_name);
        $this->name = $name;
        $this->username = strtolower($user_name);
        $this->email = $email;
        $this->password = $password;
        $this->id = $this->generate_user_id();
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
        return substr(md5(uniqid(rand(), true)), 0, 8);
    }

    #validate all user data
    public function validate_login_user_data()
    {
        if (empty($this->login)) {
            throw new CustomException("Username or Email is required", 400);
        }
        if (empty($this->password)) {
            throw new CustomException("Password is required", 400);
        }
        if (strlen($this->password) < 8) {
            throw new CustomException("Password must be at least 8 characters", 400);
        }
        //add validation to check if email already exists
        if (!$this->get_user_by_email($this->email) && Validator::validate_email($this->email)) {
            throw new CustomException("Email does not exists", 400);
        }
        //add validation to check if username already exists
        if (!$this->get_user_by_username($this->username)) {
            throw new CustomException("Username does not exists", 400);
        }
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
        if (!Validator::validate_email($this->email)) {
            throw new CustomException("Invalid email format", 400);
        }
        if (strlen($this->password) < 8) {
            throw new CustomException("Password must be at least 8 characters", 400);
        }
        //add validation to check if email already exists
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
                    'id' => $this->id,
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
            $user_data = Validator::validate_email($this->email) ? $this->get_user_by_email($this->login) : $this->get_user_by_username($this->login);
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
            $_SESSION['username'] = $data['username'];
            $_SESSION['name'] = $data['name'];
            $_SESSION['email'] = $data['email'];
            $_SESSION['user_id'] = $data['id'];
            $_SESSION['user_unique'] = $data['unique_id'];
        
    }

    public static function any_is_logged_in(){
        return isset($_SESSION['username']) && 
        isset($_SESSION['email']) &&
        isset($_SESSION['name']) &&
        isset($_SESSION['user_id']) &&
        isset($_SESSION['user_unique']) ;
    }

    
}