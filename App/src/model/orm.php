<?php
namespace model;

use auth\LoginRol;
use Exception;
use PDO;

final class ORMModel {
    protected $conn;
    protected $id;
    protected $table;
    private $client;

    public function __construct(PDO $conn, int $id, string $table) {
        $this->conn = $conn;
        $this->id = $id;
        $this->table = $table;
        $this->client = new LoginRol($conn);
    }

    public function getAll():array {
        try {
            $sqlQuery = "SELECT * FROM $this->table";
            $stmt = $this->conn->prepare($sqlQuery);

            if(!$stmt->execute()) {
                $response = $stmt->errorInfo();
                throw new Exception("Error: " . $response[2]);
            }
            
            return $stmt->fetchAll();
        } catch (Exception $error) {
            throw new Exception("Error:".$error->getMessage());
        }
    }

    public function insert(array $data):string {
        try {
            $columns = implode(",", array_keys($data));
            $placeholder = implode(",", array_fill(0, count($data), "?"));

            $sqlQuery = "INSERT INTO $this->table ($columns) VALUES ($placeholder);";

            $stmt = $this->conn->prepare($sqlQuery);
            
            if(!$stmt->execute()) {
                $response = $stmt->errorInfo();
                throw new Exception("Error: " . $response[2]);
            }

            return "Datos insertados con exito";
        } catch(Exception $error) {
            throw new Exception("Error: " . $error->getMessage());
        }
    }
}