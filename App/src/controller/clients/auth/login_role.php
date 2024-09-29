<?php 
require __DIR__."/../../../config/connection.php";
session_start();

class Client
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function cleanInput($data): string
    {
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }

    public function auth($data): void
    {
        try {
            if (!isset($data["email"]) || !isset($data["password"])) {
                http_response_code(400);
                echo json_encode(["message" => "Email or password not found"]);
                exit;
            }

            $email = $this->cleanInput($data["email"]);
            $pass = $this->cleanInput($data["password"]);

            $stmt = $this->conn->prepare("SELECT * FROM gestor_vuelos.client WHERE email_u = :email AND role_id = 1;");
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);

            if ($stmt->execute()) {

                $user = $stmt->fetch(); 

                if (password_verify($pass, $user["password"])) {
                    $_SESSION["name"] = $user["name"];
                    $_SESSION["email"] = $user["email_u"];
                    $_SESSION["role"]  = $user["role_id"];

                    http_response_code(200);
                    echo json_encode(["message" => "Welcome User"]);
                    exit;
                } else {
                    http_response_code(401);
                    echo json_encode(["message" => "Invalid credentials"]);
                    exit;
                }
            } else {
                http_response_code(400);
                $response = $stmt->errorInfo();
                echo json_encode(["message" => $response[2]]);
                exit;
            }
        } catch (PDOException $error) {
            http_response_code(500);
            echo json_encode(["message" => $error->getMessage()]);
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $input = file_get_contents("php://input");
    $data = json_decode($input, true); 

    if (json_last_error() === JSON_ERROR_NONE) {
        $login = new Client($openSQL->conn);
        $login->auth($data);
    } else {
        http_response_code(400);
        echo json_encode(["message" => "Invalid JSON format"]);
        exit;
    }
}
