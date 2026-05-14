<?php
require_once '../Core/Model.php';

class Vehicle extends Model {
    protected $table = 'vehicles';

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

    public function create($plateNumber, $type, $model) {
        $plateNumber = $this->escape($plateNumber);
        $type = $this->escape($type);
        $model = $this->escape($model);
        
        $sql = "INSERT INTO {$this->table} (plate_number, type, model)
                VALUES ('$plateNumber', '$type', '$model')";
        
        return $this->query($sql);
    }

    public function update($id, $plateNumber, $type, $model) {
        $id = intval($id);
        $plateNumber = $this->escape($plateNumber);
        $type = $this->escape($type);
        $model = $this->escape($model);
        
        $sql = "UPDATE {$this->table} SET
                plate_number='$plateNumber',
                type='$type',
                model='$model'
                WHERE id=$id";
        
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
