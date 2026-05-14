<?php

class Model {
    protected $conn;
    protected $table;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    protected function query($sql) {
        return $this->conn->query($sql);
    }

    protected function prepare($sql) {
        return $this->conn->prepare($sql);
    }

    protected function escape($string) {
        return $this->conn->real_escape_string($string);
    }

    public function getLastInsertId() {
        return $this->conn->insert_id;
    }

    public function getError() {
        return $this->conn->error;
    }
}
?>
