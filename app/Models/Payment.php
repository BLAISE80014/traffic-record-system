<?php
require_once '../Core/Model.php';

class Payment extends Model {
    protected $table = 'payments';

    public function getAll() {
        $sql = "SELECT * FROM {$this->table} ORDER BY id DESC";
        return $this->query($sql);
    }

    public function getById($id) {
        $id = intval($id);
        $sql = "SELECT * FROM {$this->table} WHERE id=$id";
        $result = $this->query($sql);
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }

    public function create($driverId, $amount) {
        $driverId = intval($driverId);
        $amount = floatval($amount);
        
        $sql = "INSERT INTO {$this->table} (driver_id, amount)
                VALUES ($driverId, $amount)";
        
        return $this->query($sql);
    }

    public function delete($id) {
        $id = intval($id);
        $sql = "DELETE FROM {$this->table} WHERE id=$id";
        return $this->query($sql);
    }

    public function getTotal() {
        $sql = "SELECT COUNT(*) AS total FROM {$this->table}";
        $result = $this->query($sql);
        return (int) $result->fetch_assoc()['total'];
    }
}
?>
