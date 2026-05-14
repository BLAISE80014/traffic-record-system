<?php

class Database {
    private $host = "localhost";
    private $user = "root";
    private $pass = "";
    private $db = "traffic_system";
    private $conn;

    public function connect() {
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->db);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }

        return $this->conn;
    }

    public function getConnection() {
        return $this->conn;
    }
}
?>
