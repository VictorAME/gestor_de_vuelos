<?php
require __DIR__ . '/../../index.php';

class Connection {

    public $conn;
    private $user;
    private $pass;
    private $host;
    private $db;
    private $dns;

    public function __construct($user, $pass, $host, $db) {
        $this->user = $user;
        $this->pass = $pass;
        $this->host = $host;
        $this->db = $db;
        $this->dns = 'mysql:host='.$this->host.';dbname='.$this->db;
    }

    public function openSQL_PDO() {
        try {
            $this->conn = new PDO($this->dns, $this->user, $this->pass);
            $stmt = $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $error) {
            header('HTTP/1.1 500 Interval Server Error');
            echo json_encode(["Error" => $error]);
        }
    }
}

//Var SQL:
$db = $_ENV['ENV_SERVER_DATABASE'];
$host = $_ENV['ENV_SERVER_HOST']; 
$user = $_ENV['ENV_SERVER_USERNAME'];
$pass = $_ENV['ENV_SERVER_PASSWORD'];

$openSQL = new Connection($user, $pass, $host, $db);
$openSQL->openSQL_PDO();