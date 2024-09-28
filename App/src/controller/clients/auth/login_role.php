<?php 
namespace auth;

use PDO;
use PDOException;

$input = file_get_contents('php://input');
$user = json_decode($input, true);

// Clase base que maneja la conexión a la base de datos
class LoginRol 
{
    public $conn;

    public function __construct($conn) 
    {
        $this->conn = $conn;
    }
}

// Clase para clientes con rol de usuario
class ClientUser extends LoginRol 
{
    public function auth(array $data): string
    {
        try {
            // Validación de datos
            if (!isset($data["email"]) || !isset($data["password"])) {
                http_response_code(400);
                return json_encode(["message" => "Email or password not found"]);
            }

            // Consulta SQL para el rol de usuario (rol_id = 1)
            $sqlQuery = "SELECT email_u, contraseña FROM gestor_vuelos.usuarios WHERE email_u = :email AND rol_id = 1";
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->bindParam(":email", $data["email"], PDO::PARAM_STR);

            // Ejecutar consulta
            if ($stmt->execute()) {
                $user = $stmt->fetch();

                // Verificación de contraseña
                if ($user && password_verify($data["password"], $user["contraseña"])) {
                    http_response_code(200);
                    return json_encode(["message" => "Welcome User"]);
                } else {
                    http_response_code(401);
                    return json_encode(["message" => "Invalid credentials"]);
                }
            } else {
                http_response_code(400);
                $response = $stmt->errorInfo();
                return json_encode(["message" => $response[2]]);
            }
        } catch (PDOException $error) {
            http_response_code(500);
            return json_encode(["message" => $error->getMessage()]);
        }
    }
}

// Clase para clientes con rol de administrador
class ClientAdmin extends LoginRol 
{
    public function auth(array $data): string
    {
        try {
            // Validación de datos
            if (!isset($data["email"]) || !isset($data["password"])) {
                http_response_code(400);
                return json_encode(["message" => "Email or password not found"]);
            }

            // Consulta SQL para el rol de administrador (rol_id = 2)
            $sqlQuery = "SELECT email_u, contraseña FROM gestor_vuelos.usuarios WHERE email_u = :email AND rol_id = 2";
            $stmt = $this->conn->prepare($sqlQuery);
            $stmt->bindParam(":email", $data["email"], PDO::PARAM_STR);

            // Ejecutar consulta
            if ($stmt->execute()) {
                $admin = $stmt->fetch();

                // Verificación de contraseña
                if ($admin && password_verify($data["password"], $admin["contraseña"])) {
                    http_response_code(200);
                    return json_encode(["message" => "Welcome Admin"]);
                } else {
                    http_response_code(401);
                    return json_encode(["message" => "Invalid credentials"]);
                }
            } else {
                http_response_code(400);
                $response = $stmt->errorInfo();
                return json_encode(["message" => $response[2]]);
            }
        } catch (PDOException $error) {
            http_response_code(500);
            return json_encode(["message" => $error->getMessage()]);
        }
    }
}

if(isset($user["role"]) && $user["role"] === "Admin") {
    $admin = new ClientAdmin($conn);
    echo $admin->auth($user);
} else {
    $client = new ClientUser($conn);
    echo $client->auth($user);
}