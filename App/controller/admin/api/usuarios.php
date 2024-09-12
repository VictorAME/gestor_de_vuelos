<?php
include '../../../model/config/connection.php';

header('Content-Type: application/json');
$input = file_get_contents('php://input');
$data = json_decode($input, true);

class Usuarios_API {
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

    // public function getId() {

    // }

    public function show() {
        try {
            $query = 'SELECT * FROM user;';
            $stmt = $this->conn->prepare($query);
    
            if (!$stmt->execute()) {
                header('HTTP/1.1 400 Bad Request');
                $response = $stmt->errorInfo();
                echo json_encode(["message" => $response[2]]);
                exit();
            }
    
            $rows = $stmt->fetchAll();

            $data = ["items" => []];

            foreach ($rows as $row) {
                $data["items"][] = [
                    "id" => $row["user_id"],
                    "name" => $row["name_u"],
                    "lastname" => $row["lastname_u"],
                    "email" => $row["email_u"],
                    "password" => $row["pass_u"],
                ];
            }
            header("HTTP/1.1 200 OK");
            echo json_encode($data);
    
        } catch (PDOException $error) {
            // Manejar excepciones y errores de la base de datos
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(["message" => $error->getMessage()]);
        }
    }
    

    public function insert($data) {
        try {
            if(!isset($data['nombre']) || !isset($data['apellidos']) || !isset($data['correo']) || !isset($data['contrasena'])) {
                echo json_encode(["message" => $data]);
                return;
            }

            $query = 'INSERT INTO user (name_u, lastname_u, email_u, pass_u) VALUES (:nombre, :apellidos, :correo, :contrasena);';

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

    public function delete($id) {
        try {
            if (empty($id) || !is_numeric($id)) {
                header('HTTP/1.1 400 Bad Request');
                echo json_encode(["message" => "El ID no está pasando o no es válido."]);
                return;
            }
    
            $query = 'DELETE FROM user WHERE user_id = :user_id;';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':user_id', $id, PDO::PARAM_INT);
    
            if (!$stmt->execute()) {
                header('HTTP/1.1 400 Bad Request');
                $response = $stmt->errorInfo();
                echo json_encode(["message" => $response[2]]);
                return;
            }
    
            if ($stmt->rowCount() === 0) {
                header('HTTP/1.1 404 Not Found');
                echo json_encode(["message" => "Cuenta no encontrada."]);
                return;
            }
    
            header('HTTP/1.1 200 OK');
            echo json_encode(["message" => "Usuario eliminado correctamente."]);
        } catch (PDOException $error) {
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(["message" => $error->getMessage()]);
        }
    }
    

    public function update($update) {
        try {
            if( empty($update['name_v']) || empty($update['tipo_v']) || empty($update['precio_v']) || empty($update['disponibles_v']) ) {
                echo json_encode(["message" => "Los parametros no estan pasando"]);
                return;
            }

            $query = 'UPDATE user SET nombre_u = :nombre_v,
            tipo_v = :tipo_v,
            precio_v = :precio_v,
            disponibles_v = :disponibles_v
            WHERE vuelos_id = :vuelos_id';

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':vuelos_id', $update['vuelos_id'], PDO::PARAM_STR);
            $stmt->bindParam(':nombre', $update['nombre_v'], PDO::PARAM_STR);
            $stmt->bindParam(':tipo', $update['tipo_v'], PDO::PARAM_STR);
            $stmt->bindParam(':precio', $update['precio_v'], PDO::PARAM_STR);
            $stmt->bindParam(':disponibles', $update['disponibles_v'], PDO::PARAM_STR);

            if(!$stmt->execute()) {
                header('HTTP/1.1 400 Bad Request');
                $response = $stmt->errorInfo();
                echo json_encode(["message" => $response[2]]);
                return;
            }

            $row = $stmt->fetch();

            $update = [
                "id" => $row['vuelo_id'],
                "nombre" => $row['nombre_v'],
                "tipo" => $row['tipo_v'],
                "precio" => $row['precio_v'],
                "disponibles" => $row['disponibles_v'],
            ];

            header('HTTP/1.1 200 OK');
            echo json_encode(["items" => $update]);
        } catch(PDOException $error) {
            header('HTTP/1.1 500 Interval Server Error');
            echo json_encode(["message" => $error->getMessage()]);
        }   
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $getUser = new Usuarios_API($openSQL->conn);
    $getUser->show();
}
else if($_SERVER["REQUEST_METHOD"] === "POST") {
    $singin = new Usuarios_API($openSQL->conn);
    $singin->insert($data);
}
else if ($_SERVER['REQUEST_METHOD'] === "DELETE") {
    $delete = new Usuarios_API($openSQL->conn);

    $id = isset($_GET['user_id']) ? $_GET['user_id'] : null;

    if ($id) {
        $delete->delete($id);
    } else {
        header('HTTP/1.1 400 Bad Request');
        echo json_encode(["message" => "ID de usuario no proporcionado o no válido."]);
    }
}
// UPDATE
// else if()