<?php include "../../../config/connection.php";

class BuscadorVuelos {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function securityData($data) {
        $data = trim($data);
        $data = htmlspecialchars($data);
        return;
    }

    public function getVuelos($search):void {
        try {
            if(!isset($search["destino"])) {
                http_response_code(400);
                echo json_encode(["message" => "Los parámetros no son correctos"]);
                exit();
            }
    
            $query = "SELECT destino, origen, vuelos_id, usuario_id, fecha_ida, fecha_regreso, hora_salida, hora_llegada, precio 
                      FROM gestor_vuelos.vuelos 
                      WHERE destino LIKE :destino";
    
            $destino = "%" . $this->securityData($search["destino"]) . "%";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":destino", $destino, PDO::PARAM_STR);
    
            // Ejecutar la consulta
            if(!$stmt->execute()) {
                $response = $stmt->errorInfo();
                echo json_encode(["error" => $response[2]]);
                http_response_code(500);
                exit();
            }
    
            // Verificar si se encontró algún resultado
            $row = $stmt->fetch();
            
            (!$row) ? http_response_code(404) : http_response_code(200);
    
            // Crear el array de resultados
            $search = [
                "id" => $row["vuelos_id"],
                "usuario" => $row["usuario_id"],
                "destino" => $row["destino"],
                "origen" => $row["origen"],
                "fechaIda" => $row["fecha_ida"],
                "fechaRegreso" => $row["fecha_regreso"],
                "horaSalida" => $row["hora_salida"],
                "horaLlegada" => $row["hora_llegada"],
                "precio" => $row["precio"],
            ];
    
            // Devolver el resultado
            http_response_code(200);
            echo json_encode(["items" => [$search]]);
        } catch(PDOException $error) {
            http_response_code(500);
            echo json_encode(["error" => $error->getMessage()]);
        }
    }
}  
if($_SERVER["REQUEST_METHOD"] === "POST") {
    $like = new BuscadorVuelos($openSQL->conn);

    $input = file_get_contents("php://input");
    $search = json_decode($input, true);

    if(json_last_error_msg() === JSON_ERROR_NONE && $search != null) {
        $like->getVuelos($search);
    } else {
        echo json_encode(["Error: " => json_last_error_msg()]);
    }
}