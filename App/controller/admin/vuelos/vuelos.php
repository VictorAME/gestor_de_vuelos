<?php
include '../../../model/config/connection.php';

$input = file_get_contents('php://input');
$data = json_decode($input, true);

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
                "id" => $row['vuelo_id'],
                "nombre" => $row['nombre_v'],
                "tipo" => $row['tipo_v'],
                "precio" => $row['precio_v'],
                "disponibles" => $row['disponibles_v'],
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
            
            if( !$stmt->execute() ) {
                header('HTTP/1.1 400 Bad Request');
                $response = $stmt->errorInfo();
                echo json_encode(["message" => $response[2]]);
                return;
            }

            $data = $stmt->fetchAll();
            header('HTTP/1.1 200 OK');
            echo json_encode($data);
        } catch(PDOException $error) {
            header('HTTP/1.1 500 Interval Server Error');
            echo json_encode(["message" => $error->getMessage()]);
        }
    }

    public function insert($insert) {
        try {
            if( empty($insert['nombre_v']) || empty($insert['tipo_v']) || empty($insert['precio']) || empty($insert['disponibles']) ) {
                echo json_encode(["message" => "No hay valores en los parametros"]);
                return;
            }

            $query = 'INSERT INTO vuelos (nombre_v, tipo_v, precio_v, disponibilidad_v) VALUES (:vuelo, :tipo, :precio, :disponibles);';

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':vuelo', $insert['nombre_v'], PDO::PARAM_STR);
            $stmt->bindParam(':tipo', $insert['tipo_v'], PDO::PARAM_STR);
            $stmt->bindParam(':precio', $insert['precio'], PDO::PARAM_STR);
            $stmt->bindParam(':disponibles', $insert['disponibles'], PDO::PARAM_STR);
            
            if( !$stmt->execute() ) {
                header('HTTP/1.1 400 Bad Request');
                $response = $stmt->errorInfo();
                echo json_encode(["message" => $response[2]]);
                return;
            }

            header('HTTP/1.1 200 OK');
            echo json_encode(["message" => "Datos insertados"]);
        } catch(PDOException $error) {
            header('HTTP/1.1 500 Interval Server Error');
            echo json_encode(["message" => $error->getMessage()]);
        }
    }

    public function delete($delete) {
        try {
            if( empty($delete['id']) ) {
                echo json_encode(["message" => "El ID no esta pasando"]);
                return;
            }

            $query = 'DELETE FROM vuelos WHERE vuelos_id = :id;';
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
                "id" => $row['vuelo_id'],
                "nombre" => $row['nombre_v'],
                "tipo" => $row['tipo_v'],
                "precio" => $row['precio_v'],
                "disponibles" => $row['disponibles_v'],
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

            $query = 'UPDATE vuelos SET nombre_v = :nombre_v,
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
if( $_SERVER['REQUEST_METHOD'] === 'GER' ) {
    if(isset($id)) {
        $get = new VuelosAPI($pdo->conexa);
        $get->getId($id);
    } else {
        $getAll = new VuelosAPI($pdo->conexa);
        $getAll->show();
    }
}
else if( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
    $set = new VuelosAPI($pdo->conexa);
    $set->insert($data);
}
else if( $_SERVER['REQUEST_METHOD'] === 'DELETE' ) {
    $delete = new VuelosAPI($pdo->conexa);
    $delete->delete($data);
}
else if( $_SERVER['REQUEST_METHOD'] === 'PUT' ) {
    $update = new VuelosAPI($pdo->conexa);
    $update->update($data);
}