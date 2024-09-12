<?php
// include '../../model/config/connection.php';
// $input = file_get_contents('php://input');
// $data = json_decode($input, true);

// class Validation {
//     private $conn;

//     public function __construct($conn) {
//         $this->conn = $conn;
//     }

//     public function securityData($data) {
//         $data = trim($data);
//         $data = htmlspecialchars($data);
//         return $data;
//     }

//     public function getId($id) {
//         try {

//             if( empty($id['id']) ) {
//                 echo json_encode(["message" => "El ID no esta pasando"]);
//                 return;
//             }

//             $query = 'SELECT * FROM user;';
//             $stmt = $this->conn->prepare($query);
//             $stmt->bindParam(':id', $id['id'], PDO::PARAM_STR);

//             if(!$stmt->execute()) {
//                 header('HTTP/1.1 400 Bad Request');
//                 $response = $stmt->errorInfo();
//                 echo json_encode(["message" => $response[2]]);
//             }

//             $row = $stmt->fetch();

//             $id = [
//                 "id" => $row['user_id'],
//                 "name" => $row['name_u'],
//                 "lastname" => $row['lastname_u'],
//                 "email" => $row['email_u'],
//                 "password" => $row['pass_u'],
//             ];
//             header('HTTP/1.1 200 OK');
//             echo json_encode(["items" => $id['id']]);
//         } catch(PDOException $error) {
//             header('HTTP/1.1 500 Interval Server Error');
//             echo json_encode(["message" => $error->getMessage()]);
//         }
//     }

//     public function show() {
//         try {
//             $query = 'SELECT * FROM user;';

//             $stmt = $this->conn->prepare($query);
            
//             if(!$stmt->execute()) {
//                 header('HTTP/1.1 400 Bad Request');
//                 $response = $stmt->errorInfo();
//                 echo json_encode(["message" => $response[2]]);
//             }

//             $response = $stmt->fetchAll();

//             header('HTTP/1.1 200 OK');
//             echo json_encode($response);
//         } catch(PDOException $error) {
//             header('HTTP/1.1 500 Interval Server Error');
//             echo json_encode(["message" => $error->getMessage()]);
//         }
//     }

//     public function login($data) {
//         try {
//             if (empty($data["email"]) || empty($data["password"])) {
//                 echo json_encode(["message" => "Los parámetros están vacíos"]);
//                 var_dump($data);
//                 return;
//             }
    
//             $query = 'SELECT * FROM user WHERE email_u = :email;';
//             $stmt = $this->conn->prepare($query);
//             $stmt->bindParam(":email", $data["email"], PDO::PARAM_STR);
            
//             if (!$stmt->execute()) {
//                 header('HTTP/1.1 400 Bad Request');
//                 $response = $stmt->errorInfo();
//                 echo json_encode(["message" => $response[2]]);
//                 return;
//             }
    
//             if (intval($stmt->rowCount()) === 0) {
//                 header('HTTP/1.1 404 Not Found');
//                 echo json_encode(["message" => "Cuenta no encontrada"]);
//                 return;
//             }
    
//             $user = $stmt->fetch();
            
//             if (password_verify($data["password"], $user["pass_u"])) {
//                 header('HTTP/1.1 200 OK');
//                 echo json_encode(["message" => "Bienvenido"]);
//             } else {
//                 header('HTTP/1.1 401 Unauthorized');
//                 echo json_encode(["message" => "Contraseña incorrecta"]);
//             }
//         } catch (PDOException $error) {
//             header('HTTP/1.1 500 Internal Server Error');
//             echo json_encode(["message" => $error->getMessage()]);
//         }
//     }
// }

// if ($_SERVER['REQUEST_METHOD'] === 'GET') {
//     $getUser = new Validation($openSQL->conn);
//     $getUser->getId(["items" => $id]);
// }
// else if($_SERVER["REQUEST_METHOD"] === "POST") {
//     $val = new Validation($openSQL->conn);
//     $val->login($data);
// }
?>