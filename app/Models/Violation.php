<?php
require_once '../Core/Model.php';

class Violation extends Model {
    protected $table = 'violations';

    public function getAll() {
        $sql = "SELECT * FROM {$this->table} ORDER BY date DESC";
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

    public function create($driverId, $type, $date) {
        $driverId = intval($driverId);
        $type = $this->escape($type);
        $date = $this->escape($date);
        
        $sql = "INSERT INTO {$this->table} (driver_id, type, date)
                VALUES ($driverId, '$type', '$date')";
        
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

    public function getLast30Days() {
        $sql = "SELECT COUNT(*) AS total FROM {$this->table}
                WHERE date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
        $result = $this->query($sql);
        return (int) $result->fetch_assoc()['total'];
    }

    public function getByDayLast30Days() {
        $sql = "SELECT DATE(date) AS d, COUNT(*) AS total FROM {$this->table}
                WHERE date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                GROUP BY DATE(date)";
        return $this->query($sql);
    }
}
?>
