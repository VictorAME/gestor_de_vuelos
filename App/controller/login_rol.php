<?php include "../model/config/connection.php";
session_start();
$input = file_get_contents('php://input');
$user = json_decode($input, true);

// if (json_last_error() !== JSON_ERROR_NONE) {
//     header('HTTP/1.1 400 Bad Request');
//     echo json_encode(["message" => "Entrada JSON no válida"]);
//     exit();
// }

class LoginRol {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function autenticacion($user) {
        try {
            if (!isset($user['email']) || !isset($user['password'])) {
                http_response_code(400);
                echo json_encode(["message" => "Los parámetros no deben estar vacíos"]);
                exit();
            }

            $query = "SELECT email_u, contraseña FROM usuarios WHERE email_u = :email";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':email', $user['email'], PDO::PARAM_STR);

            if (!$stmt->execute()) {
                http_response_code(400);
                $response = $stmt->errorInfo();
                echo json_encode(["message" => $response[2]]);
                exit();
            }

            if ($stmt->rowCount() === 0) {
                http_response_code(404);
                echo json_encode(["message" => "No se encontró esta cuenta."]);
                exit();
            }

            $user = $stmt->fetch();

            if (password_verify($user['password'], $user['pass_u'])) {
                $_SESSION['email'] = $user['email_u'];
                $_SESSION['role'] = $user['rol_id'];
                
                http_response_code(200);
                if ($_SESSION['role'] === 2) {
                    echo json_encode(["message" => "Eres admin"]);
                } 
                else if ($_SESSION['role'] === 1){
                    echo json_encode(["message" => "Eres usuario"]);
                }
            } else {
                http_response_code(401);
                echo json_encode(["message" => "Contraseña incorrecta"]);
            }

        } catch (PDOException $error) {
            http_response_code(500);
            echo json_encode(["message" => $error->getMessage()]);
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = new LoginRol($openSQL->conn);
    $login->autenticacion($user);
}