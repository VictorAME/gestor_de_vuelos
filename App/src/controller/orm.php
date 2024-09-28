<?php
    final class ORM {
        protected $id;
        protected $table;
        protected $conn;

        public function __construct($id, $table, PDO $conn)
        {  
            $this->id = $id;
            $this->table = $table;
            $this->conn = $conn; 
        }

        public function getAll(): string {
            $stm = $this->conn->prepare("SELECT * FROM {$this->table}");
            $stm->execute();
            return $stm->fetchAll();
        }
    }