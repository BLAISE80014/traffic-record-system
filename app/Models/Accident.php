<?php
require_once '../Core/Model.php';

class Accident extends Model {
    protected $table = 'accidents';

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

    public function create($driverId, $vehicleId, $location, $date, $description) {
        $driverId = intval($driverId);
        $vehicleId = intval($vehicleId);
        $location = $this->escape($location);
        $date = $this->escape($date);
        $description = $this->escape($description);
        
        $sql = "INSERT INTO {$this->table} (driver_id, vehicle_id, location, date, description)
                VALUES ($driverId, $vehicleId, '$location', '$date', '$description')";
        
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
