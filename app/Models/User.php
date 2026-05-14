<?php
require_once '../Core/Model.php';

class User extends Model {
    protected $table = 'users';

    public function create($name, $email, $password) {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $name = $this->escape($name);
        $email = $this->escape($email);
        
        $sql = "INSERT INTO {$this->table} (name, email, password)
                VALUES ('$name', '$email', '$password')";
        
        return $this->query($sql);
    }

    public function findByEmail($email) {
        $email = $this->escape($email);
        $sql = "SELECT * FROM {$this->table} WHERE email='$email'";
        $result = $this->query($sql);
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }

    public function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
}
?>
