<?php
include "../config/connection.php";
header('Content-Type:application/json');

class VuelosAPI {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getId($id) {
        try {
            $query = 'SELECT * FROM vuelos WHERE vuelos_id = :id;';

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id['id'], PDO::PARAM_INT);
            
            if( !$stmt->execute() ) {
                header('HTTP/1.1 400 Bad Request');
                $response = $stmt->errorInfo();
                echo json_encode(["message" => $response[2]]);
                return;
            }

            if( intval($stmt->rowCount()) === 0 ) {
                header('HTTP/1.1 404 Not Found');
                echo json_encode(["message" => "Cuenta no encontrada"]);
                return;
            }

            $row = $stmt->fetch();

            $id = [
                "id" => $row["vuelo_id"],
                "nombre" => $row["usuario_id"],
                "tipo" => $row["destino_v"],
                "precio" => $row["origen_v"],
                "disponibles" => $row["fecha_ida"],
                "fechaRegreso" => $row["fecha_regreso"],
                "horaSalida" => $row["hora_salida"],
                "horaLlegada" => $row["hora_llegada"],
                "precio" => $row["precio"],
            ];

            header('HTTP/1.1 200 OK');
            echo json_encode(["items" => $id]);
        } catch(PDOException $error) {
            header('HTTP/1.1 500 Interval Server Error');
            echo json_encode(["message" => $error->getMessage()]);
        }
    }

    public function show() {
        try {
            $query = 'SELECT * FROM vuelos;';

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
                    "id" => $row["vuelo_id"],
                    "nombre" => $row["usuario_id"],
                    "tipo" => $row["destino_v"],
                    "precio" => $row["origen_v"],
                    "disponibles" => $row["fecha_ida"],
                    "fechaRegreso" => $row["fecha_regreso"],
                    "horaSalida" => $row["hora_salida"],
                    "horaLlegada" => $row["hora_llegada"],
                    "precio" => $row["precio"],
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

    public function insert($insert) {
        try {
            echo json_encode(["datos recibidos" => $insert]);

            if( !isset($insert["usuario"]) || !isset($insert["destino"]) || !isset($insert["origen"]) || !isset($insert["fechaIda"]) || !isset($insert["fechaRegreso"]) || !isset($insert["horaSalida"]) || !isset($insert["horaLlegada"]) || !isset($insert["precio"])) {
                echo json_encode(["message" => "No hay valores en los parametros"]);
                return;
            }

            $query = "INSERT INTO vuelos (usuario_id, destino_v, origen_v, fecha_ida, fecha_regreso, hora_salida, hora_llegada, precio) 
            VALUES (:usuario, :destino, :origen, :fechaIda, :fechaRegreso, :horaSalida, :horaLlegada, :precio);";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":usuario", $insert["usuario"], PDO::PARAM_INT);
            $stmt->bindParam(":destino", $insert["destino"], PDO::PARAM_STR);
            $stmt->bindParam(":origen", $insert["origen"], PDO::PARAM_STR);
            $stmt->bindParam(":fechaIda", $insert["fechaIda"], PDO::PARAM_STR);
            $stmt->bindParam(":fechaRegreso", $insert["fechaRegreso"], PDO::PARAM_STR);
            $stmt->bindParam(":horaSalida", $insert["horaSalida"], PDO::PARAM_STR);
            $stmt->bindParam(":horaLlegada", $insert["horaLlegada"], PDO::PARAM_STR);
            $stmt->bindParam(":precio", $insert["precio"], PDO::PARAM_STR);
            
            if( !$stmt->execute() ) {
                header("HTTP/1.1 400 Bad Request");
                $response = $stmt->errorInfo();
                echo json_encode(["message" => $response[2]]);
                return;
            }

            header("HTTP/1.1 200 OK");
            echo json_encode(["message" => "Datos insertados"]);
        } catch(PDOException $error) {
            header("HTTP/1.1 500 Interval Server Error");
            echo json_encode(["message" => $error->getMessage()]);
        }
    }

    public function delete($delete) {
        try {
            if( empty($delete['id']) ) {
                echo json_encode(["message" => "El ID no esta pasando"]);
                return;
            }

            $query = 'DELETE FROM vuelos WHERE boleto_id = :id;';
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $delete['id'], PDO::PARAM_INT);

            if( !$stmt->execute() ) {
                header('HTTP/1.1 400 Bad Request');
                $response = $stmt->errorInfo();
                echo json_encode(["message" => $response[2]]);
                return;
            }

            $row = $stmt->fetch();

            $data = [
                "id" => $row["vuelo_id"],
                "nombre" => $row["usuario_id"],
                "tipo" => $row["destino_v"],
                "precio" => $row["origen_v"],
                "disponibles" => $row["fecha_ida"],
                "fechaRegreso" => $row["fecha_regreso"],
                "horaSalida" => $row["hora_salida"],
                "horaLlegada" => $row["hora_llegada"],
                "precio" => $row["precio"],
            ];

            header('HTTP/1.1 200 OK');
            echo json_encode(["items" => $delete]);
        } catch(PDOException $error) {
            header('HTTP/1.1 500 Interval Server Error');
            echo json_encode(["message" => $error->getMessage()]);
        }
    }

    public function update($update) {
        try {
            if( empty($update['name_v']) || empty($update['tipo_v']) || empty($update['precio_v']) || empty($update['disponibles_v']) ) {
                echo json_encode(["message" => "Los parametros no estan pasando"]);
                return;
            }

            $query = 'UPDATE vuelos SET vuelo_id = :vuelo,
            cliente_id = :cliente,
            estado = :estado,
            fecha_compra = :fecha
            WHERE boleto_id = :boleto';

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':vuelo', $update['vuelo'], PDO::PARAM_STR);
            $stmt->bindParam(':cliente', $update['cliente'], PDO::PARAM_STR);
            $stmt->bindParam(':estado', $update['tipo_v'], PDO::PARAM_STR);
            $stmt->bindParam(':fecha', $update['fecha'], PDO::PARAM_STR);

            if(!$stmt->execute()) {
                header('HTTP/1.1 400 Bad Request');
                $response = $stmt->errorInfo();
                echo json_encode(["message" => $response[2]]);
                return;
            }

            $row = $stmt->fetch();

            $update = [
            "id" => $row["vuelo_id"],
            "nombre" => $row["usuario_id"],
            "tipo" => $row["destino_v"],
            "precio" => $row["origen_v"],
            "disponibles" => $row["fecha_ida"],
            "fechaRegreso" => $row["fecha_regreso"],
            "horaSalida" => $row["hora_salida"],
            "horaLlegada" => $row["hora_llegada"],
            "precio" => $row["precio"],
            ];

            header('HTTP/1.1 200 OK');
            echo json_encode(["items" => $update]);
        } catch(PDOException $error) {
            header('HTTP/1.1 500 Interval Server Error');
            echo json_encode(["message" => $error->getMessage()]);
        }   
    }
} 
if( $_SERVER['REQUEST_METHOD'] === 'GET' ) {
    $getAll = new VuelosAPI($openSQL->conn);
    $getAll->show();
}
else if( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    echo json_encode(["input_crudo" => $input, "json_decodificado" => $data]);

    if (json_last_error() === JSON_ERROR_NONE && $data !== null) {
        $setUser = new Usuarios_API($openSQL->conn);
        $setUser->insert($data);
    } else {
        echo json_encode(["Error" => json_last_error_msg()]);
    }
}
else if( $_SERVER['REQUEST_METHOD'] === 'DELETE' ) {
    $delete = new VuelosAPI($openSQL->conn);
    $delete->delete($data);
}
else if( $_SERVER['REQUEST_METHOD'] === 'PUT' ) {
    $update = new VuelosAPI($openSQL->conn);
    $update->update($data);
}