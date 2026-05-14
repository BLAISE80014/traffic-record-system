<?php
require_once '../Core/Controller.php';

class DriverController extends Controller {
    private $driverModel;

    public function __construct() {
        $this->driverModel = $this->model('Driver');
    }

    public function index() {
        $search = $_GET['search'] ?? '';
        
        if (!empty($search)) {
            $drivers = $this->driverModel->search($search);
        } else {
            $drivers = $this->driverModel->getAll();
        }
        
        return ['drivers' => $drivers, 'search' => $search];
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save'])) {
            $name = $_POST['name'] ?? '';
            $license = $_POST['license'] ?? '';
            $dob = $_POST['dob'] ?? '';
            $gender = $_POST['gender'] ?? '';
            $phone = $_POST['phone'] ?? '';

            if (!empty($name) && !empty($license)) {
                $this->driverModel->create($name, $license, $dob, $gender, $phone);
                header("Location: dashboard.php#driver");
                exit();
            }
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
            $id = $_POST['id'] ?? '';
            $name = $_POST['name'] ?? '';
            $license = $_POST['license'] ?? '';
            $dob = $_POST['dob'] ?? '';
            $gender = $_POST['gender'] ?? '';
            $phone = $_POST['phone'] ?? '';

            if (!empty($id) && !empty($name)) {
                $this->driverModel->update($id, $name, $license, $dob, $gender, $phone);
                header("Location: dashboard.php#driver");
                exit();
            }
        }
    }

    public function delete() {
        if (isset($_GET['delete'])) {
            $id = $_GET['delete'];
            $this->driverModel->delete($id);
            header("Location: dashboard.php#driver");
            exit();
        }
    }

    public function edit() {
        if (isset($_GET['edit'])) {
            $id = $_GET['edit'];
            return $this->driverModel->getById($id);
        }
        return null;
    }
}
?>
