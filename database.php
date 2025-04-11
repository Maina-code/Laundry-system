<?php 
require_once 'db.php';
class Database{
    private $host;
    private $dbuser;
    private $dbpassword;
    private $dbname;
    public $conn;

    public function __construct() {
        $this->host = DB_HOST;
        $this->dbuser = DB_USER;
        $this->password = DB_PASSWORD;
        $this->dbname = DB_NAME;

        $this->conn = new mysqli($this->host, $this->dbuser, $this->password, $this->dbname);
        if($this->conn->connect_error){
            die("Connection failed: " . $this->conn->connect_error);
        }
    }
    public function getConnection() {
        return $this->conn;
    }

    public function query($sql){
        return $this->conn->query($sql);
    }
} 
?>