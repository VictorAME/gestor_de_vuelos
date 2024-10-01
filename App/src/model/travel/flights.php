<?php require __DIR__."../../../config/connection.php";
header("Content-Type: application/json");

$input = file_get_contents('php://input');
$data = json_decode($input, true);

class VuelosAPI {
    private $conn;

    public function __construct($conn) 
    {
        $this->conn = $conn;
    }

    public function cleanInput($data):string 
    {
        return htmlspecialchars(trim($data), ENT_QUOTES, "UTF-8");
    }

    public function getId($id):array 
    {
        try {
            if(!isset($id["id"])) {
                http_response_code(400);
                echo json_encode(["message" => "El ID no esta pasando"]);
                exit;
            }

            $stmt = $this->conn->prepare("SELECT * FROM vuelos WHERE vuelos_id = :id;");
            $stmt->bindParam(':id', $id['id'], PDO::PARAM_INT);
            
            if( !$stmt->execute() ) {
                http_response_code(400);
                $response = $stmt->errorInfo();
                echo json_encode(["message" => $response[2]]);
                exit;
            }

            if( $stmt->rowCount() === 0 ) {
                http_response_code(404);
                echo json_encode(["message" => "Cuenta no encontrada"]);
                exit;
            }

            // $row = $stmt->fetch();

            // $id = [
            //     "id" => $row["vuelo_id"],
            //     "origen" => $row["origen_v"],
            //     "destino" => $row["destino_v"],
            //     "F_salida" => $row["fecha_salida"],
            //     "F_regreso" => $row["fecha_regreso"],
            //     "H_salida" => $row["hora_salida"],
            //     "H_llegada" => $row["hora_llegada"],
            //     "precio" => $row["precio"],
            // ];

            http_response_code(200);
            return $stmt->fetch();
        } catch(PDOException $error) {
            http_response_code(500);
            echo json_encode(["message" => $error->getMessage()]);
        }
    }

    public function show():void 
    {
        try {
            $stmt = $this->conn->prepare('SELECT * FROM gestor_vuelos.vuelos;');
    
            if (!$stmt->execute()) {
                http_response_code(400);
                $response = $stmt->errorInfo();
                echo json_encode(["message" => $response[2]]);
                exit;
            }
    
            $rows = $stmt->fetchAll();

            $data = ["items" => []];

            foreach ($rows as $row) {
                $data["items"][] = [
                    "id" => $row["vuelos_id"],
                    "origen" => $row["origen_v"],
                    "destino" => $row["destino_v"],
                    "F_salida" => $row["fecha_salida"],
                    "F_regreso" => $row["fecha_regreso"],
                    "H_salida" => $row["hora_salida"],
                    "H_llegada" => $row["hora_llegada"],
                    "precio" => $row["precio"],
                ];
            }
            http_response_code(200);
            echo json_encode($data);
        } catch (PDOException $error) {
            http_response_code(500);
            echo json_encode(["error" => $error->getMessage()]);
        }
    }

    public function insert($insert): string
{
    try {
        if (!isset($insert["origen"]) || !isset($insert["destino"]) || !isset($insert["fechaIda"]) || !isset($insert["fechaRegreso"]) || !isset($insert["horaSalida"]) || !isset($insert["horaLlegada"]) || !isset($insert["precio"])) {
            return json_encode(["message" => "No hay valores en los parametros"]);
        }

        $origen = $this->cleanInput($insert["origen"]);
        $destino = $this->cleanInput($insert["destino"]);
        $f_salida = $this->cleanInput($insert["fechaIda"]);
        $f_regreso = $this->cleanInput($insert["fechaRegreso"]);
        $h_salida = $this->cleanInput($insert["horaSalida"]);
        $h_llegada = $this->cleanInput($insert["horaLlegada"]);
        $precio = $this->cleanInput($insert["precio"]);

        $stmt = $this->conn->prepare("INSERT INTO vuelos (origen_v, destino_v, fecha_salida, fecha_regreso, hora_salida, hora_llegada, precio) 
        VALUES (:origen, :destino, :fechaIda, :fechaRegreso, :horaSalida, :horaLlegada, :precio);");
        $stmt->bindParam(":origen", $origen, PDO::PARAM_STR);
        $stmt->bindParam(":destino", $destino, PDO::PARAM_STR);
        $stmt->bindParam(":fechaIda", $f_salida, PDO::PARAM_STR);
        $stmt->bindParam(":fechaRegreso", $f_regreso, PDO::PARAM_STR);
        $stmt->bindParam(":horaSalida", $h_salida, PDO::PARAM_STR);
        $stmt->bindParam(":horaLlegada", $h_llegada, PDO::PARAM_STR);
        $stmt->bindParam(":precio", $precio, PDO::PARAM_STR); // Considerar cambiar a PDO::PARAM_INT si es adecuado
        
        if (!$stmt->execute()) {
            http_response_code(400);
            $response = $stmt->errorInfo();
            return json_encode(["message" => $response[2]]);
        }

        http_response_code(200);
        return json_encode(["message" => "Datos insertados"]);
    } catch (PDOException $error) {
        http_response_code(500);
        return json_encode(["message" => $error->getMessage()]);
    }
}


    public function delete($delete):string 
    {
        try {
            if( !isset($delete["vuelo_id"]) ) {
                echo json_encode(["message" => "El ID no esta pasando"]);
                exit;
            }

            $stmt = $this->conn->prepare('DELETE FROM vuelos WHERE vuelos_id = :id;');
            $stmt->bindParam(':id', $delete['vuelo_id'], PDO::PARAM_INT);

            if( !$stmt->execute() ) {
                http_response_code(400);
                $response = $stmt->errorInfo();
                echo json_encode(["message" => $response[2]]);
                exit;
            }

            http_response_code(200);
            return json_encode(["message" => "Dato eliminado"]);
        } catch(PDOException $error) {
            http_response_code(500);
            echo json_encode(["message" => $error->getMessage()]);
        }
    }

    //Falta hacer que funcione (Aun no se checo si funciona o no).
    public function update($update):string 
    {
        try {
            if( empty($update['name_v']) || empty($update['tipo_v']) || empty($update['precio_v']) || empty($update['disponibles_v']) ) {
                echo json_encode(["message" => "Los parametros no estan pasando"]);
                exit;
            }

            $query = 'UPDATE vuelos SET vuelo_id = :vuelo,
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
                exit;
            }

            $row = $stmt->fetch();

            $update = [
                "id" => $row["vuelo_id"],
                "origen" => $row["origen_v"],
                "destino" => $row["destino_v"],
                "F_salida" => $row["fecha_salida"],
                "F_regreso" => $row["fecha_regreso"],
                "H_salida" => $row["hora_salida"],
                "H_llegada" => $row["hora_llegada"],
                "precio" => $row["precio"],
            ];

            header('HTTP/1.1 200 OK');
            return json_encode(["items" => ""]);
        } catch(PDOException $error) {
            header('HTTP/1.1 500 Interval Server Error');
            echo json_encode(["message" => $error->getMessage()]);
        }   
    }
} 

if( $_SERVER['REQUEST_METHOD'] === 'GET' ) 
{
    $getAll = new VuelosAPI($openSQL->conn);
    $getAll->show();
} 
else if($_SERVER['REQUEST_METHOD'] === "POST") 
{
    if (json_last_error() === JSON_ERROR_NONE) {
        $setUser = new VuelosAPI($openSQL->conn);
        $setUser->insert($data);
    } else {
        echo json_encode(["Error" => json_last_error_msg()]);
    }
}
else if($_SERVER["REQUEST_METHOD"] === "DELETE") 
{
    $delete = new VuelosAPI($openSQL->conn);
    // $id = isset($_GET["vuelo_id"]) ? $_GET["vuelo_id"] : null;
    $delete->delete($data);
}
else if($_SERVER["REQUEST_METHOD"] === "PUT")
{
    /*....*/
} 