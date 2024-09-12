<?php 
// include '../../model/config/connection.php';
include "../model/config/connection.php";
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
            if (empty($user['email']) || empty($user['password'])) {
                header('HTTP/1.1 400 Bad Request');
                echo json_encode(["message" => "Los parámetros no deben estar vacíos"]);
                exit();
            }

            $query = 'SELECT u.id, u.email_u, u.pass_u, r.role_name 
                      FROM user u 
                      JOIN roles r ON u.role_id = r.role_id 
                      WHERE u.email_u = :email';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':email', $user['email'], PDO::PARAM_STR);

            if (!$stmt->execute()) {
                header('HTTP/1.1 400 Bad Request');
                $response = $stmt->errorInfo();
                echo json_encode(["message" => $response[2]]);
                exit();
            }

            if (intval($stmt->rowCount()) === 0) {
                header('HTTP/1.1 404 Not Found');
                echo json_encode(["message" => "No se encontró esta cuenta."]);
                exit();
            }

            $pass = $stmt->fetch();

            if (password_verify($user['password'], $pass['pass_u'])) {
                $_SESSION['email'] = $pass['email_u'];
                $_SESSION['role'] = $pass['role_name'];
                
                header('HTTP/1.1 200 OK');
                if ($_SESSION['role'] === 'admin') {
                    echo json_encode(["message" => "Eres admin"]);
                } else {
                    echo json_encode(["message" => "Eres usuario"]);
                }
            } else {
                header('HTTP/1.1 401 Unauthorized');
                echo json_encode(["message" => "Contraseña incorrecta"]);
            }

        } catch (PDOException $error) {
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(["message" => $error->getMessage()]);
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = new LoginRol($openSQL->conn);
    $login->autenticacion($user);
}