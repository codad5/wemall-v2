<?php
namespace Codad5\Wemall\Helper;
$dontenv = \Dotenv\Dotenv::createImmutable(__DIR__.'/../../');
$dontenv->load();


Class Db{
    private $host;
    private $user;
    private $db;
    private $password;
    private \PDO $pdo;

    public function __construct()
    {
        $this->host = $_ENV['DB_HOST'];
        $this->user = $_ENV['DB_USER'];
        $this->db = $_ENV['DATABASE'];
        $this->password = $_ENV['DB_PASS'];
        try{
            $this->connect();
        }
        catch(\PDOException $e){
            if($e->getMessage() == "SQLSTATE[HY000] [1049] Unknown database '".$this->db."'"){
                $this->initializeDb();
                $this->connect();
            }
            else{
                
            }

        }
    }

    public function connect()
    {
        $dsn = 'mysql:host='.$this->host.';db='.$this->db;
        $pdo = new \PDO($dsn, $this->user, $this->password);
        $pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
        $this->pdo = $pdo;
        return $pdo;
    }

    private function initializeDb()
    {
        $sql_setup = file_get_contents('db.sql');
        if(!$sql_setup){
            $conn = new \mysqli($this->host, $this->user, $this->password);
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            $sql = "CREATE DATABASE ".$this->db.";";
            if ($conn->query($sql) === TRUE) {         
                $stmt = $this->connect()->prepare($sql_setup);
                if(!$stmt->execute(array())){
                    $stmt = null;
                    return false;
                    
                }
            }
        }
    }

    public function query_data(string $sql,array $params = []) : \PDOStatement
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function select_data(string $sql,array $params = []) : array
    {
        $stmt = $this->query_data($sql, $params);
        return $stmt->fetchAll();
    }


}