<?php require __DIR__."../../../config/connection.php";
header("Content-Type: application/json");

final class Usuarios_API {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function passwordHash($data):string {
        return password_hash($data, PASSWORD_BCRYPT);
    }
    
    public function cleanInput($data):string {
        return htmlspecialchars(trim($data), ENT_QUOTES, "UTF-8");
    }

    public function show():void {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM gestor_vuelos.client;");
    
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
                    "id" => $row["user_id"],
                    "nombre" => $row["name"],
                    "apellidos" => $row["lastname"],
                    "telefono" => $row["phone"],
                    "correo" => $row["email_u"],
                    "contraseña" => $row["password"],
                    "rol" => $row["role_id"],
                ];
            }
            http_response_code(200);
            echo json_encode($data);
        } catch (PDOException $error) {
            http_response_code(500);
            echo json_encode(["error" => $error->getMessage()]);
        }
    }
    
    public function insert($data):void 
    {
        try {
            if(!isset($data["name"]) || !isset($data["lastname"]) || !isset($data["phone"]) || !isset($data["email"]) || !isset($data["password"])) {
                echo json_encode(["message" => "Los parametros no estan pasando"]);
                exit;
            }

            $name = $this->cleanInput($data["name"]);
            $lastname = $this->cleanInput($data["lastname"]);
            $phone = $this->cleanInput($data["phone"]);
            $email = $this->cleanInput($data["email"]);
            $pass = $this->passwordHash($data["password"]);

            $stmt = $this->conn->prepare("INSERT INTO gestor_vuelos.client (name, lastname, phone, email_u, password, role_id) 
            VALUES (:name, :lastname, :phone, :email, :password, 2);");

            $stmt->bindParam(":name", $name, PDO::PARAM_STR);
            $stmt->bindParam(":lastname", $lastname, PDO::PARAM_STR);
            $stmt->bindParam(":phone", $phone, PDO::PARAM_STR);
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->bindParam(":password", $pass, PDO::PARAM_STR);
            
            if(!$stmt->execute()) {
                http_response_code(400);
                $response = $stmt->errorInfo();
                echo json_encode(["message" => $response[2]]);
                exit;
            } 
            
            http_response_code(200);
            echo json_encode(["message" => "Datos insertados con exito"]);
        } catch(PDOException $error) {
            http_response_code(500);
            echo json_encode(["message" => $error->getMessage()]);
        }
    }

    public function delete($id):string {
        try {
            if (empty($id)) {
                echo json_encode(["warning" => "El ID no está pasando o no es válido."]);
                exit();
            }
            
            $stmt = $this->conn->prepare("DELETE FROM gestor_vuelos.client WHERE user_id = :usuario_id;");
            $stmt->bindParam(':usuario_id', $id["usuario_id"], PDO::PARAM_INT);
    
            if (!$stmt->execute()) {
                http_response_code(400);
                $response = $stmt->errorInfo();
                echo json_encode(["message" => $response[2]]);
                exit();
            }

            http_response_code(200); 
            return json_encode(["message" => "User delete"]);
        } catch (PDOException $error) {
            http_response_code(500);
            echo json_encode(["message" => $error->getMessage()]);
        }
    }
    
    public function update($update):string {
        try {
            echo json_encode(["datos recibidos" => $update]);

            if(!isset($update["nombre_u"]) || !isset($update["apellidos_u"]) || !isset($update["telefono_u"]) || !isset($update["email_u"])) {
                echo json_encode(["warning" => "Los parametros no estan pasando"]);
                exit();
            }

            $stmt = $this->conn->prepare("UPDATE usuarios SET nombre_u = :nombre_u,
            apellidos_u = :apellidos_u,
            telefono_u = :telefono_u,
            email_u = :email_u
            WHERE usuario_id = :usuario_id;");

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
            return json_encode(["message" => "User updating"]);
        } catch(PDOException $error) {
            http_response_code(500);
            echo json_encode(["message" => $error->getMessage()]);
        }   
    }
}

/* @GET */
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $usuarios = new Usuarios_API($openSQL->conn);
    $usuarios->show();
} 

/* @POST */
else if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    if (json_last_error() === JSON_ERROR_NONE) {
        $singin = new Usuarios_API($openSQL->conn);
        $singin->insert($data);
    } else {
        http_response_code(400);
        echo json_encode(["Error" => json_last_error_msg()]);
        exit;
    }
} 

/* @DELETE */
else if ($_SERVER['REQUEST_METHOD'] === "DELETE") {
    $delete = new Usuarios_API($openSQL->conn);
    $id = isset($_GET["usuario_id"]) ? $_GET["usuario_id"] : null;
    $delete->delete(["usuario_id" => $id]);
} 

/* @PUT */
else if($_SERVER['REQUEST_METHOD'] === "PUT") {
    $update = new Usuarios_API($openSQL->conn);

    $id = isset($_GET["usuario_id"]) ? $_GET["usuario_id"] : null;
    $nombre = isset($_GET["nombre_u"]) ? $_GET["nombre_u"] : null;
    $apellidos = isset($_GET["apellidos_u"]) ? $_GET["apellidos_u"] : null;
    $telefono = isset($_GET["telefono_u"]) ? $_GET["telefono_u"] : null;
    $correo = isset($_GET["email_u"]) ? $_GET["email_u"] : null;

    $update->update([
        "usuario_id" => $id,
        "nombre_u" => $nombre,
        "apellidos_u" => $apellidos,
        "telefono_u" => $telefono,
        "email_u" => $correo
    ]);
}