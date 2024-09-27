<?php 

include "../../../config/connection.php";

$input = file_get_contents('php://input');
$data = json_decode($input, true);

class VuelosPago {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function seleccionVuelo($data) {
        try {
            if( empty($data['origen']) || empty($data['destino']) || empty($data['fecha_ida']) || empty($data['fecha_regreso']) ) {
                echo json_encode(["message" => "Los parametros no estan pasando."]);
                return;
            }

            $query = 'SELECT * FROM vuelos ';
        } catch(PDOException $error) {
            header('HTTP/1.1 500 Interval Server Error');
            echo json_encode(["message" => $error->getMessage()]);
        }
    }
}