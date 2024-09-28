<?php
header("Content-Type: application/json");

final class Usuarios_API {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function securityPassword($data) {
        return password_hash($data, PASSWORD_BCRYPT);
    }
    
    public function securityData($data) {
        $data = trim($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    public function show():void {
        try {
            $query = 'SELECT * FROM usuarios;';
            $stmt = $this->conn->prepare($query);
    
            if (!$stmt->execute()) {
                http_response_code(400);
                $response = $stmt->errorInfo();
                echo json_encode(["warning" => $response[2]]);
                exit();
            }
    
            $rows = $stmt->fetchAll();

            $data = ["items" => []];

            foreach ($rows as $row) {
                $data["items"][] = [
                    "id" => $row["usuario_id"],
                    "nombre" => $row["nombre_u"],
                    "apellidos" => $row["apellidos_u"],
                    "telefono" => $row["telefono_u"],
                    "correo" => $row["email_u"],
                    "contraseña" => $row["contraseña"],
                    "rol" => $row["rol_id"],
                ];
            }
            http_response_code(200);
            echo json_encode($data);
        } catch (PDOException $error) {
            // Manejar excepciones y errores de la base de datos
            http_response_code(500);
            echo json_encode(["error" => $error->getMessage()]);
        }
    }
    
    public function insert($data) {
        try {
            echo json_encode(["datos recibidos" => $data]);

            if(!isset($data["nombre"]) || !isset($data["apellidos"]) || !isset($data["telefono"]) || !isset($data["correo"]) || !isset($data["contrasena"])) {
                echo json_encode(["warning" => "Los parametros no estan pasando"]);
                exit();
            }

            $query = "INSERT INTO usuarios (nombre_u, apellidos_u, telefono_u, email_u,contraseña, rol_id) 
            VALUES (:nombre, :apellidos, :telefono, :correo, :contrasena, 1);";

            $pass = $this->securityPassword($data["contrasena"]);

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":nombre", $data["nombre"], PDO::PARAM_STR);
            $stmt->bindParam(":apellidos", $data["apellidos"], PDO::PARAM_STR);
            $stmt->bindParam(":telefono", $data["telefono"], PDO::PARAM_STR);
            $stmt->bindParam(":contrasena", $pass, PDO::PARAM_STR);
            $stmt->bindParam(":correo", $data["correo"], PDO::PARAM_STR);
            
            if(!$stmt->execute()) {
                http_response_code(400);
                $response = $stmt->errorInfo();
                echo json_encode(["warning" => $response[2]]);
                exit();
            } else {
                http_response_code(200);
                echo json_encode(["message" => "Datos insertados con exito"]);
            }
        } catch(PDOException $error) {
            http_response_code(500);
            echo json_encode(["error" => $error->getMessage(), "code" => $error->getCode()]);
        }
    }

    public function delete($id) {
        try {
            if (empty($id)) {
                echo json_encode(["warning" => "El ID no está pasando o no es válido."]);
                exit();
            }
    
            $query = 'DELETE FROM usuarios WHERE usuario_id = :usuario_id;';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':usuario_id', $id["usuario_id"], PDO::PARAM_INT);
    
            if (!$stmt->execute()) {
                http_response_code(400);
                $response = $stmt->errorInfo();
                echo json_encode(["message" => $response[2]]);
                exit();
            }
    
            if ($stmt->rowCount() === 0) {
                http_response_code(404);
                echo json_encode(["message" => "Cuenta no encontrada."]);
                exit();
            }
            
            $row = $stmt->fetch();

            $id = [
                "id" => $row["usuario_id"],
                "nombre" => $row["nombre_u"],
                "apellidos" => $row["apellidos_u"],
                "telefono" => $row["telefono_u"],
                "correo" => $row["email_u"],
                "contraseña" => $row["contraseña"],
                "rol" => $row["rol_id"],
            ];

            http_response_code(200);
            echo json_encode(["items" => [$id]]);
        } catch (PDOException $error) {
            http_response_code(500);
            echo json_encode(["message" => $error->getMessage()]);
        }
    }
    
    public function update($update) {
        try {
            echo json_encode(["datos recibidos" => $update]);

            if(!isset($update["nombre_u"]) || !isset($update["apellidos_u"]) || !isset($update["telefono_u"]) || !isset($update["email_u"])) {
                echo json_encode(["warning" => "Los parametros no estan pasando"]);
                exit();
            }
    
            $query = 'UPDATE usuarios SET nombre_u = :nombre_u,
            apellidos_u = :apellidos_u,
            telefono_u = :telefono_u,
            email_u = :email_u
            WHERE usuario_id = :usuario_id';

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':nombre_u', $update['nombre_u'], PDO::PARAM_STR);
            $stmt->bindParam(':apellidos_u', $update['apellidos_u'], PDO::PARAM_STR);
            $stmt->bindParam(':telefono_u', $update['telefono_u'], PDO::PARAM_STR);
            $stmt->bindParam(':email_u', $update['email_u'], PDO::PARAM_STR);
            $stmt->bindParam(':usuario_id', $update['usuario_id'], PDO::PARAM_INT);
    
            if(!$stmt->execute()) {
                http_response_code(400);
                $response = $stmt->errorInfo();
                echo json_encode(["message" => $response[2]]);
                exit();
            }
    
            $row = $stmt->fetch();
    
            $update = [
                "id" => $row["usuario_id"],
                "nombre" => $row["nombre_u"],
                "apellidos" => $row["apellidos_u"],
                "telefono" => $row["telefono_u"],
                "correo" => $row["email_u"],
                "contraseña" => $row["contraseña"],
                "rol" => $row["rol_id"],
            ];
    
            http_response_code(200);
            echo json_encode(["items" => [$update]]);
        } catch(PDOException $error) {
            http_response_code(500);
            echo json_encode(["message" => $error->getMessage()]);
        }   
    }
}

/* @GET */
// if ($_SERVER['REQUEST_METHOD'] === 'GET') {
//     $usuarios = new Usuarios_API($openSQL->conn);
//     $usuarios->show();
// } 

/* @POST */
// else if ($_SERVER["REQUEST_METHOD"] === "POST") {
//     $input = file_get_contents('php://input');
//     $data = json_decode($input, true);

//     echo json_encode(["input_crudo" => $input, "json_decodificado" => $data]);

//     if (json_last_error() === JSON_ERROR_NONE && $data !== null) {
//         $singin = new Usuarios_API($openSQL->conn);
//         $singin->insert($data);
//     } else {
//         echo json_encode(["Error" => json_last_error_msg()]);
//     }
// } 

/* @DELETE */
// else if ($_SERVER['REQUEST_METHOD'] === "DELETE") {
//     $delete = new Usuarios_API($openSQL->conn);
//     $id = isset($_GET["usuario_id"]) ? $_GET["usuario_id"] : null;
//     $delete->delete(["usuario_id" => $id]);
// } 

/* @PUT */
// else if($_SERVER['REQUEST_METHOD'] === "PUT") {
//     $update = new Usuarios_API($openSQL->conn);

//     $id = isset($_GET["usuario_id"]) ? $_GET["usuario_id"] : null;
//     $nombre = isset($_GET["nombre_u"]) ? $_GET["nombre_u"] : null;
//     $apellidos = isset($_GET["apellidos_u"]) ? $_GET["apellidos_u"] : null;
//     $telefono = isset($_GET["telefono_u"]) ? $_GET["telefono_u"] : null;
//     $correo = isset($_GET["email_u"]) ? $_GET["email_u"] : null;

//     $update->update([
//         "usuario_id" => $id,
//         "nombre_u" => $nombre,
//         "apellidos_u" => $apellidos,
//         "telefono_u" => $telefono,
//         "email_u" => $correo
//     ]);
// }