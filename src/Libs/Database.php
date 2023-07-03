<?php
namespace Codad5\Wemall\Libs;
use Codad5\Wemall\Libs\Exceptions\CustomException;
use Codad5\Wemall\Libs\Helper\Helper;
use Codad5\Wemall\Enums\{APPError};
class Database
{
    private static Database $instance;
    readonly private \PDO $pdo;
    private static string $table;
    private ErrorHandler $errorHandler;


    public function __construct($table = null, $config = [])
    {
        $this->errorHandler = new ErrorHandler('pdo-error', false, $_SERVER["DOCUMENT_ROOT"]."/log/db.log");
        self::$table = $table;
        if(empty($config))
        {
            $config = [
                'host' => $_ENV['DB_HOST'],
                'user' => $_ENV['DB_USER'],
                'db' => $_ENV['DATABASE'],
                'password' => $_ENV['DB_PASS'],
                'charset'   => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix'    => '',
            ];
        }
        $this->pdo = $this->connect($config);
        self::$instance = $this;
        
    }
    private function connect(array $config)
    {
        try {
            $dsn = 'mysql:host='.$config['host'].';dbname='.$config['db'];
            $pdo = new \PDO($dsn, $config['user'], $config['password']);
            $pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
            return $pdo;
            } catch (\PDOException $e) {
            if($e->getMessage() == "SQLSTATE[HY000] [1049] Unknown database '".$config['db']."'"){
                $this->initializeDb($config);
                return $this->connect($config);
            }
            else{
                $this->errorHandler->handleException($e);
                ViewLoader::load_error_page(500, "something went wrong on our side");
                die();
            }
        }
    }
    private function initializeDb($config) : void
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
                    return;
                    
                }
            }
        }
    }
    public static function getInstance(string $table = null) :self
    {
        if (!isset(self::$instance)) {
            return new self($table ?? isset(self::$table) ? self::$table : '');
        }
        return self::$instance;
    }

    public static function table($table): Database
    {
        self::$table = $table;
        return self::getInstance();
    }

    /**
     * @throws CustomException
     */
    public static function all(): false|array
    {
        $query = "SELECT * FROM " . self::$table;
        return self::query($query)->fetchAll();
    }

    /**
     * @throws CustomException
     */
    public static function where($column, $value, $operator = "=") : array|null
    {
//        echo "reaches herer";
        $query = "SELECT * FROM " . self::$table . " WHERE $column $operator ?";
        return self::query($query, is_array($value) ? $value : [$value])?->fetchAll();
    }

    /**
     * @throws CustomException
     */
    public static function find($id)
    {
        $query = "SELECT * FROM " . self::$table . " WHERE id = ?";
        return self::query($query, [$id])->fetch();
    }

    /**
     * @throws CustomException
     */
    public static function select($query, $binding = []): false|array
    {
        return self::query($query, $binding)->fetchAll();
    }

    /**
     * To query data from db
     * @throws CustomException
     */
    public static function query($query, $bindings = []) :null|\PDOStatement
    {
        $stmt = null;
        try{
            $stmt = self::getInstance()->pdo->prepare($query);
            $stmt->execute(is_array($bindings) ? $bindings : [$bindings]);
        }
        catch(\PDOException $e){
            (new ErrorHandler('pdo-error', false, $_SERVER["DOCUMENT_ROOT"]."/logs/db.log"))->handleException($e);
            throw new CustomException('server error', 500);
        }
        return $stmt;
    }

}
