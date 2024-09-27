<?php require __DIR__ . "../../../../index.php";

class Connection {

    public $conn;
    private string $user;
    private string $pass;
    private string $host;
    private string $db;
    private string $dsn;

    public function __construct(
        string $user,
        string $pass,
        string $host,
        string $db
    ) {
        $this->user = $user;
        $this->pass = $pass;
        $this->host = $host;
        $this->db = $db;
        $this->dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->db;
    }

    public function openSQL_PDO(): void {
        try {
            $this->conn = new PDO($this->dsn, $this->user, $this->pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error en la conexión: " . $e->getMessage();
        }
    }
}

$host = $_ENV['ENV_CONFIG_HOST']; 
$db = $_ENV['ENV_CONFIG_DATABASE'];
$user = $_ENV['ENV_CONFIG_USERNAME'];
$pass = $_ENV['ENV_CONFIG_PASSWORD'];

$openSQL = new Connection($user, $pass, $host, $db);
$openSQL->openSQL_PDO();
