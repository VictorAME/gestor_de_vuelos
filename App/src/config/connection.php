<?php 
namespace App\src\config;

use PDO;
use PDOException;

class Connection {

    public  $conn;
    private $user;
    private $pass;
    private $host;
    private $db;
    private $dsn;

    public function __construct(string $user, string $pass, string $host, string $db) 
    {
        $this->user = $user;
        $this->pass = $pass;
        $this->host = $host;
        $this->db = $db;
        $this->dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->db;
    }

    public function openSQL_PDO():void 
    {
        try {
            $this->conn = new PDO($this->dsn, $this->user, $this->pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            http_response_code(200);
        } catch (PDOException $e) {
            http_response_code(500);
            echo "Error en la conexión: " . $e->getMessage();
        }
    }
}

$openSQL = new Connection($user, $pass, $host, $db);
$openSQL->openSQL_PDO();
