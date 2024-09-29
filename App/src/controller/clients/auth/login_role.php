<?php 
require __DIR__."/../../../config/connection.php";
session_start();

$input = file_get_contents("php://input");
$data = json_decode($input, true);

class Client
{
    public $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }
}

class ClientLogin extends Client 
{
    public function cleanInput($data): string
    {
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }

    public function auth($data):void
    {
        try 
        {
            if (!isset($data["email"]) || !isset($data["password"])) {
                http_response_code(400);
                echo json_encode(["message" => "Email or password not found"]);
                exit;
            }

            $email = $this->cleanInput($data["email"]);
            $pass = $this->cleanInput($data["password"]);

            $stmt = $this->conn->prepare("SELECT * FROM gestor_vuelos.client WHERE email_u = :email;");
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);

            if ($stmt->execute()) 
            {
                $user = $stmt->fetch(); 

                if (password_verify($pass, $user["password"])) {
                    $_SESSION["email"] = $user["email_u"];
                    $_SESSION["role"]  = $user["role_id"];
                    $_SESSION["name"]  = $user["name"];

                    if($_SESSION["role"] === 2) {
                        http_response_code(200);
                        echo json_encode(["message" => "Welcome Admin"]);
                        exit;
                    } else {
                        http_response_code(200);
                        echo json_encode(["message" => "Welcome User"]);
                        exit;
                    }
                } else {
                    http_response_code(401);
                    echo json_encode(["message" => "Invalid credentials"]);
                    exit;
                }
            } 
            else 
            {
                http_response_code(400);
                $response = $stmt->errorInfo();
                echo json_encode(["message" => $response[2]]);
                exit;
            }
        } 
        catch (PDOException $error) 
        {
            http_response_code(500);
            echo json_encode(["message" => $error->getMessage()]);
        }
    }
}

final class ClientHome extends Client 
{
    function username():void
    {
        if (isset($_SESSION["name"])) {
            http_response_code(200);
            echo json_encode(["username" => $_SESSION["name"]]);
        } else {
            http_response_code(401);
            echo json_encode(["message" => "User not logged in"]);
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {  
    if (json_last_error() === JSON_ERROR_NONE) {
        $login = new ClientLogin($openSQL->conn);
        $login->auth($data);
    } else {
        http_response_code(400);
        echo json_encode(["message" => "Invalid JSON format"]);
        exit;
    }
}

if($_SERVER["REQUEST_METHOD"] === "GET") {
    $home = new ClientHome($openSQL->conn);
    $home->username();
}
