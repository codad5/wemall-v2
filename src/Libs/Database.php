<?php
namespace Codad5\Wemall\Libs;
use Codad5\Wemall\Libs\Helper\Helper;
$dontenv = \Dotenv\Dotenv::createImmutable(__DIR__.'/../../');
$dontenv->load();
class Database
{
    private static $instance;
    private \PDO $pdo;
    private static $table;
    private $errorHandler;
   

    public function __construct($table = null, $config = [])
    {
        $this->errorHandler = new ErrorHandler('pdo-error');
        self::$table = $table;
        $config = [
            'host' => $_ENV['DB_HOST'],
            'user' => $_ENV['DB_USER'],
            'db' => $_ENV['DATABASE'],
            'password' => $_ENV['DB_PASS'],
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ];
        $this->pdo = $this->connect($config);
        
    }
    private function connect(array $config)
    {
        try {
            $dsn = 'mysql:host='.$config['host'].';dbname='.$config['db'];
            $pdo = new \PDO($dsn, $config['user'], $config['password']);
            $pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
            $this->pdo = $pdo;
            return $pdo;
            } catch (\PDOException $e) {
            if($e->getMessage() == "SQLSTATE[HY000] [1049] Unknown database '".$config['db']."'"){
                $this->initializeDb($config);
                return $this->connect($config);
            }
            else{
                echo $e->getMessage();
            }
        }
    }
    private function initializeDb($config)
    {
        $sql_setup = file_get_contents(Helper::resolve_asset('private/db.sql'));
        if($sql_setup){
            $conn = new \mysqli($config['host'], $config['user'], $config['password']);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            $sql = "CREATE DATABASE ".$config['db'].";";
            if ($conn->query($sql) === TRUE) {         
                $stmt = $this->connect($config)->prepare($sql_setup);
                if(!$stmt->execute(array())){
                    $stmt = null;
                    return false;
                    
                }
            }
        }
    }
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function table($table)
    {
        self::$table = $table;
        return self::getInstance();
    }

    public static function all()
    {
        $query = "SELECT * FROM " . self::$table;
        return self::query($query)->fetchAll();
    }

    public static function where($column, $value, $operator = "=") : array 
    {
        $query = "SELECT * FROM " . self::$table . " WHERE $column $operator ?";
        return self::query($query, [$value])->fetchAll();
    }

    public static function find($id)
    {
        $query = "SELECT * FROM " . self::$table . " WHERE id = ?";
        return self::query($query, [$id])->fetch();
    }
    public static function query($query, $bindings = [])
    {
        try{
            $stmt = self::getInstance()->pdo->prepare($query);
            $stmt->execute(...$bindings);
        }
        catch(\PDOException $e){
            (new ErrorHandler('pdo-error'))->handleException($e);
            throw new CustomException('server error');
        }finally{
            return $stmt;
        }
    }
}
