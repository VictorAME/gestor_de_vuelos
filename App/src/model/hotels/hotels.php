<?php include "../../config/connection.php";

$input = file_get_contents("php://input");
$data = json_decode($input, true);

class Hoteles_API {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function show() {
        try {
            $query = "SELECT * FROM hoteles;";

            $stmt = $this->conn->prepare($query);

            if(!$stmt->execute()) {
                http_response_code(400);
                $response = $stmt->errorInfo();
                echo json_encode(["message" => $response]);
                exit();
            }

            $data = $stmt->fetchAll();

            http_response_code(200);
            echo json_encode($data);
        } catch(PDOException $error) {
            http_response_code(500);
            echo json_encode(["message" => $error->getMessage()]);
        }
    }

    public function set($data) {
        try{
            if(!isset($data["nombre"]) || !isset($data["destino"]) || !isset($data["direccion"]) || !isset($data["estrellas_h"]) || !isset($data["precio_noche"]) || !isset($data["disponibilidad"])) {
                http_response_code(400);
                echo json_encode(["message" => "Los datos no estan pasando"]);
                exit();
            }

            $query = "INSERT INTO hoteles (nombre_h, destino_h, direccion, estrellas, precio_noche_h, disponibilidad)
            VALUES 
            (:nombre, :destino, :direccion, :estrellas_h, :precio_noche, :disponibilidad);";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":nombre", $data["nombre"], PDO::PARAM_STR);
            $stmt->bindParam(":destino", $data["destino"], PDO::PARAM_STR);
            $stmt->bindParam(":direccion", $data["direccion"], PDO::PARAM_STR);
            $stmt->bindParam(":estrellas_h", $data["estrellas_h"], PDO::PARAM_STR);
            $stmt->bindParam(":precio_noche", $data["precio_noche"], PDO::PARAM_STR);
            $stmt->bindParam(":disponibilidad", $data["disponibilidad"], PDO::PARAM_STR);

            if (!$stmt->execute()) {                
                http_response_code(400);
                $response = $stmt->errorInfo();
                echo json_encode(["message" => $response[2]]);
                exit();
            }

            http_response_code(201);
            echo json_encode(["message" => "Hotel registrado"]);
        } catch(PDOException $error) {
            http_response_code(500);
            echo json_encode(["message" => $error->getMessage()]);
        }
    }

    public function delete(){/*...*/}

    public function update(){/*...*/}
}

# @GET
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $hotel_api = new Hoteles_API($openSQL->conn);
    $hotel_api->show();
}
# @POST
else if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $hotel_api = new Hoteles_API($openSQL->conn);
    $hotel_api->set($data);
}