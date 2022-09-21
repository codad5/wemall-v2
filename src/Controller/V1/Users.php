<?php
namespace Codad5\Wemall\Controller\V1;

use Codad5\Wemall\Model\User as User;   
use Codad5\Wemall\Helper\CustomException as CustomException;
 use \Codad5\Wemall\Helper\Validators as Validator;

class Users
{
    private User $user;
    protected string $name;
    protected string $username;
    protected string $email;
    protected string $password;
    protected string $id;
    protected string $created_at;
    protected string $updated_at;
    
    
    public function __construct(
        string $user_name,
        string $name,
        string $email,
        string $password
    )
    {
        $this->name = $name;
        $this->username = $user_name;
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
        $this->id = substr(md5(uniqid(rand(), true)), 0, 8);
    }

    #validate all user data
    public function validate_signup_user_data()
    {
        if (empty($this->name)) {
            throw new CustomException("Name is required", 400);
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
            $this->user->save(
                [
                    'name' => $this->name,
                    'username' => $this->username,
                    'email' => $this->email,
                    'password' => $this->password,
                    'id' => $this->id,
                ]
            );
            );
            return $this->user;
        } catch (CustomException $e) {
            throw new CustomException($e->getMessage(), $e->getCode());
        }
    }

    public function get_user_by_id($id)
    {
        try {
            $this->user->id = $id;
            return $this->user->get_user_by_id();
        } catch (\Throwable $th) {
            throw new CustomException($th->getMessage(), 500);
        }
    }

    #get user by email
    public function get_user_by_email($email)
    {
        try {
            $this->user->email = $email;
            return $this->user->get_user_by_email();
        } catch (\Throwable $th) {
            throw new CustomException($th->getMessage(), 500);
        }
    }

    #get user by username
    public function get_user_by_username($username)
    {
        try {
            $this->user->username = $username;
            return $this->user->get_user_by_username();
        } catch (\Throwable $th) {
            throw new CustomException($th->getMessage(), 500);
        }
    }
    public function get_user_by_name($name)
    {
        try {
            $this->user->name = $name;
            return $this->user->get_user_by_name();
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
    public function login($data)
    {
        try {
            $this->user->email = $data['email'] ?? $data['username'];
            $this->user->password = $data['password'];
            return $this->user->login();
        } catch (\Throwable $th) {
            throw new CustomException($th->getMessage(), 500);
        }
    }


    
}