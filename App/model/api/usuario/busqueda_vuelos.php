<?php include "../config/connection.php";
header("Content-Type: application/json");

class Search {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function securityData($data) {
        $data = trim($data);
        $data = htmlspecialchars($data);
        return;
    }

    public function vuelos($search) {
        try {
            if(!isset($search["destino"])) {
                http_response_code(400);
                echo json_encode(["message" => "Los parametros no pasan"]);
                exit();
            }

            $query = "SELECT destino, origen FROM gestor_vuelos.vuelos WHERE destino = '%:destino%';";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":destino", $this->securityData($search["destino"]), PDO::PARAM_STR);
            $stmt->bindParam(":origen", $this->securityData($search["origen"]), PDO::PARAM_STR);

            if(!$stmt->execute()) {
                http_response_code(400);
                $response = $stmt->errorInfo();
                echo json_encode(["message" => $response[2]]);
                exit();
            }

            //condicion:
            if(intval($stmt->rowCount()) === 0) {
                http_response_code(404);
                echo json_encode(["warning" => "El vuelo que buscas no se a encotrado"]);
                exit();
            }

            $row = $stmt->fetch();

            $search = [
                "id" => $row["vuelos_id"],
                "usuario" => $row["usuario_id"],
                "destino" => $row["destino_v"],
                "origen" => $row["origen_v"],
                "fechaIda" => $row["fecha_ida"],
                "fechaRegreso" => $row["fecha_regreso"],
                "horaSalida" => $row["hora_salida"],
                "horaLlegada" => $row["hora_llegada"],
                "precio" => $row["precio"],
            ];

            http_response_code(200);
            echo json_encode(["items" => [$search]]);
        } catch(PDOException $error) {
            http_response_code(500);
            echo json_encode(["Error" => $error->getMessage()]);
        }
    }
}

if($_SERVER["REQUEST_METHOD"] === "GET") {
    $like = new Search($openSQL->conn);

    $input = file_get_contents("php://input");
    $search = json_decode($input, true);

    if(json_last_error_msg() === JSON_ERROR_NONE && $search != null) {
        $like->vuelos($search);
    } else {
        echo json_encode(["Error: " => json_last_error_msg()]);
    }
}