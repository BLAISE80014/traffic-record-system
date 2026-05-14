<?php
require_once '../Core/Controller.php';

class PaymentController extends Controller {
    private $paymentModel;

    public function __construct() {
        $this->paymentModel = $this->model('Payment');
    }

    public function index() {
        $payments = $this->paymentModel->getAll();
        return ['payments' => $payments];
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_payment'])) {
            $driverId = $_POST['driver_id'] ?? '';
            $amount = $_POST['amount'] ?? '';

            if (!empty($driverId) && !empty($amount)) {
                $this->paymentModel->create($driverId, $amount);
                echo "Payment saved!";
                header("Location: dashboard.php#payment");
                exit();
            }
        }
    }

    public function delete() {
        if (isset($_GET['p_id'])) {
            $id = $_GET['p_id'];
            $this->paymentModel->delete($id);
            header("Location: dashboard.php#payment");
            exit();
        }
    }

    public function getStats() {
        return [
            'total' => $this->paymentModel->getTotal()
        ];
    }
}
?>
