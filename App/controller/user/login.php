<?php
include '../../config/connection.php';

$input = file_get_contents('php://input');
$data = json_decode($input, true);

class Validation {
    //Objects:
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function secureteData($data) {
        $data = trim($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    public function setUser($data) {
        try {
            $query = 'SELECT * FROM user WHERE email_u = :email';

            $stmt = $this->conn->prepare($query);
            
            $email_u = $this->secureteData($data);

            $stmt->bindParam(':email', $email_u['email'], PDO::PARAM_STR);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if(password_verify($email_u['pass'], $user['pass_u'])) {
                header('HTTP/1.1 200 OK');
                echo json_encode(["message" => "Welcome"]);
            } else {
                header('HTTP/1.1 404 Not Found');
                echo json_encode(["Error:" => "Count not found"]);
            }
        } catch(PDOException $error) {
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode([$error => "Error in the Server"]);
        }
    }
}

//var:
$val = new Validation($openSQL->conn);
$val->setUser($data);