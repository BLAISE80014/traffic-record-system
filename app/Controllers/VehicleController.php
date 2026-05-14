<?php
require_once '../Core/Controller.php';

class VehicleController extends Controller {
    private $vehicleModel;

    public function __construct() {
        $this->vehicleModel = $this->model('Vehicle');
    }

    public function index() {
        $vehicles = $this->vehicleModel->getAll();
        return ['vehicles' => $vehicles];
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['saving'])) {
            $plate = $_POST['plate'] ?? '';
            $type = $_POST['type'] ?? '';
            $model = $_POST['model'] ?? '';

            if (!empty($plate) && !empty($type)) {
                $this->vehicleModel->create($plate, $type, $model);
                header("Location: dashboard.php#vehicle");
                exit();
            }
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['v_update'])) {
            $id = $_POST['id'] ?? '';
            $plate = $_POST['plate'] ?? '';
            $type = $_POST['type'] ?? '';
            $model = $_POST['model'] ?? '';

            if (!empty($id) && !empty($plate)) {
                $this->vehicleModel->update($id, $plate, $type, $model);
                header("Location: dashboard.php#vehicle");
                exit();
            }
        }
    }

    public function delete() {
        if (isset($_GET['v_delete'])) {
            $id = $_GET['v_delete'];
            $this->vehicleModel->delete($id);
            header("Location: dashboard.php#vehicle");
            exit();
        }
    }

    public function edit() {
        if (isset($_GET['v_edit'])) {
            $id = $_GET['v_edit'];
            return $this->vehicleModel->getById($id);
        }
        return null;
    }
}
?>
