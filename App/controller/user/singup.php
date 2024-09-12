<?php
include '../../model/config/connection.php';

header('Content-Type: application/json');
$input = file_get_contents('php://input');
$data = json_decode($input, true);

class Singup {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function securityPassword($data) {
        $data = password_hash($data, PASSWORD_BCRYPT);
        return $data;
    }
    
    public function securityData($data) {
        $data = trim($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    public function show() {
        try {
            $query = 'SELECT * FROM user;';

            $stmt = $this->conn->prepare($query);
            
            if(!$stmt->execute()) {
                header('HTTP/1.1 400 Bad Request');
                $response = $stmt->errorInfo();
                echo json_encode(["message" => $response[2]]);
            }

            $response = $stmt->fetchAll();

            header('HTTP/1.1 200 OK');
            echo json_encode($response);
        } catch(PDOException $error) {
            header('HTTP/1.1 500 Interval Server Error');
            echo json_encode(["message" => $error->getMessage()]);
        }
    }

    public function registro($data) {
        try {
            if(!isset($data['nombre']) || !isset($data['apellidos']) || !isset($data['correo']) || !isset($data['contrasena'])) {
                echo json_encode(["message" => $data]);
                return;
            }

            $query = 'INSERT INTO user (name_u, lastname_u, email_u, pass_u, role_id) VALUES (:nombre, :apellidos, :correo, :contrasena, 2);';

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':nombre', $this->securityData($data['nombre']), PDO::PARAM_STR);
            $stmt->bindParam(':apellidos', $this->securityData($data['apellidos']), PDO::PARAM_STR);
            $stmt->bindParam(':correo', $this->securityData($data['correo']), PDO::PARAM_STR);
            $stmt->bindParam(':contrasena', $this->securityPassword($data['contrasena']), PDO::PARAM_STR);
            
            if(!$stmt->execute()) {
                header('HTTP/1.1 400 Bad Request');
                $response = $stmt->erroInfo();
                echo json_encode(["message" => $response[2]]);
            } 

            header('HTTP/1.1 200 OK');
            echo json_encode(["message" => "Datos insertados con exito"]);
        } catch(PDOException $error) {
            header('HTTP/1.1 500 Interval Server Error');
            echo json_encode(["message" => $error->getMessage()]);
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $getUser = new Singup($openSQL->conn);
    $getUser->show();
}
else if($_SERVER["REQUEST_METHOD"] === "POST") {
    $singin = new Singup($openSQL->conn);
    $singin->registro($data);
}