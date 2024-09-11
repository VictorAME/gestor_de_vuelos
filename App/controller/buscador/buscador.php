<?php

include "../../model/config/connection.php";

$input = file_get_contents('php://input');
$data = json_decode($input, true);

class Buscador {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function buscadorVuelos() {
        try {

        } catch(PDOException $error) {

        }
    }

    public function buscadorHoteles() {
        try {

        } catch(PDOException $error) {

        }
    }
}

