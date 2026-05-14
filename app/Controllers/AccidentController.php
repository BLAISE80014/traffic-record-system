<?php
require_once '../Core/Controller.php';

class AccidentController extends Controller {
    private $accidentModel;

    public function __construct() {
        $this->accidentModel = $this->model('Accident');
    }

    public function index() {
        $accidents = $this->accidentModel->getAll();
        return ['accidents' => $accidents];
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_accident'])) {
            $driverId = $_POST['driver_id'] ?? '';
            $vehicleId = $_POST['vehicle_id'] ?? '';
            $location = $_POST['location'] ?? '';
            $date = $_POST['date'] ?? '';
            $description = $_POST['description'] ?? '';

            if (!empty($driverId) && !empty($vehicleId)) {
                $this->accidentModel->create($driverId, $vehicleId, $location, $date, $description);
                echo "Accident saved!";
                header("Location: dashboard.php#accident");
                exit();
            }
        }
    }

    public function delete() {
        if (isset($_GET['a_id'])) {
            $id = $_GET['a_id'];
            $this->accidentModel->delete($id);
            header("Location: dashboard.php#accident");
            exit();
        }
    }

    public function getStats() {
        return [
            'total' => $this->accidentModel->getTotal(),
            'last30Days' => $this->accidentModel->getLast30Days(),
            'byDay' => $this->accidentModel->getByDayLast30Days()
        ];
    }
}
?>
