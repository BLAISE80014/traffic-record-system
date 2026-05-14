<?php
require_once '../Core/Controller.php';

class ViolationController extends Controller {
    private $violationModel;

    public function __construct() {
        $this->violationModel = $this->model('Violation');
    }

    public function index() {
        $violations = $this->violationModel->getAll();
        return ['violations' => $violations];
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['driver_id']) && isset($_POST['type']) && isset($_POST['date'])) {
                $driverId = $_POST['driver_id'];
                $type = $_POST['type'];
                $date = $_POST['date'];

                $this->violationModel->create($driverId, $type, $date);
                $newId = $this->violationModel->getLastInsertId();

                // Return JSON for AJAX requests
                if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => true, 'id' => $newId]);
                    exit();
                }

                header("Location: dashboard.php#violation");
                exit();
            }
        }
    }

    public function delete() {
        if (isset($_GET['v_id'])) {
            $id = $_GET['v_id'];
            $this->violationModel->delete($id);
            header("Location: dashboard.php#violation");
            exit();
        }
    }

    public function getStats() {
        return [
            'total' => $this->violationModel->getTotal(),
            'last30Days' => $this->violationModel->getLast30Days(),
            'byDay' => $this->violationModel->getByDayLast30Days()
        ];
    }
}
?>
