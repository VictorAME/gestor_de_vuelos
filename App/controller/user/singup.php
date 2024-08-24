<?php

include '../../config/connection.php';

$input = file_get_contents('php://input');
$data = json_decode($input, true);

class Singup {
    private $conn;
    
    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function setUser() {
        try {
            $query = 'INSERT INTO user () VALUES()';


        } catch(PDOException $error) {
            header('HTTP/1.1 500 Interval Server Error');
            echo json_encode(["Error:" => $error]);
        }
    }
}