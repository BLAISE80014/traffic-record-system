<?php
require_once '../Core/Model.php';

class Driver extends Model {
    protected $table = 'drivers';

    public function getAll() {
        $sql = "SELECT * FROM {$this->table} ORDER BY id DESC";
        return $this->query($sql);
    }

    public function search($searchTerm) {
        $searchTerm = $this->escape($searchTerm);
        $sql = "SELECT * FROM {$this->table} 
                WHERE name LIKE '%$searchTerm%' 
                OR license_number LIKE '%$searchTerm%'
                ORDER BY id DESC";
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

    public function create($name, $license, $dob, $gender, $phone) {
        $name = $this->escape($name);
        $license = $this->escape($license);
        $dob = $this->escape($dob);
        $gender = $this->escape($gender);
        $phone = $this->escape($phone);
        
        $sql = "INSERT INTO {$this->table} (name, license_number, dob, gender, phone)
                VALUES ('$name', '$license', '$dob', '$gender', '$phone')";
        
        return $this->query($sql);
    }

    public function update($id, $name, $license, $dob, $gender, $phone) {
        $id = intval($id);
        $name = $this->escape($name);
        $license = $this->escape($license);
        $dob = $this->escape($dob);
        $gender = $this->escape($gender);
        $phone = $this->escape($phone);
        
        $sql = "UPDATE {$this->table} SET
                name='$name',
                license_number='$license',
                dob='$dob',
                gender='$gender',
                phone='$phone'
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
