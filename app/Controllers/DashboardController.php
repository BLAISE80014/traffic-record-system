<?php
require_once '../Core/Controller.php';

class DashboardController extends Controller {
    private $driverModel;
    private $vehicleModel;
    private $violationModel;
    private $accidentModel;
    private $paymentModel;

    public function __construct() {
        $this->driverModel = $this->model('Driver');
        $this->vehicleModel = $this->model('Vehicle');
        $this->violationModel = $this->model('Violation');
        $this->accidentModel = $this->model('Accident');
        $this->paymentModel = $this->model('Payment');
    }

    public function getSystemCounts() {
        return [
            'Drivers' => $this->driverModel->getTotal(),
            'Vehicles' => $this->vehicleModel->getTotal(),
            'Violations' => $this->violationModel->getTotal(),
            'Accidents' => $this->accidentModel->getTotal(),
            'Payments' => $this->paymentModel->getTotal(),
        ];
    }

    public function getViolationStats() {
        $byDay = [];
        $result = $this->violationModel->getByDayLast30Days();
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $byDay[$row['d']] = (int) $row['total'];
            }
        }
        
        return [
            'total' => $this->violationModel->getLast30Days(),
            'byDay' => $byDay
        ];
    }

    public function getAccidentStats() {
        $byDay = [];
        $result = $this->accidentModel->getByDayLast30Days();
        
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $byDay[$row['d']] = (int) $row['total'];
            }
        }
        
        return [
            'total' => $this->accidentModel->getLast30Days(),
            'byDay' => $byDay
        ];
    }

    public function getActivityEvents() {
        $activityEvents = [];
        $db = new Database();
        $conn = $db->getConnection();
        
        $activityRes = $conn->query(
            "SELECT 'Violation' AS category, CONCAT('Violation: ', type, ' for driver ', driver_id) AS description, date
             FROM violations
             UNION ALL
             SELECT 'Accident' AS category, CONCAT('Accident: ', location, ' for driver ', driver_id) AS description, date
             FROM accidents
             ORDER BY date DESC
             LIMIT 5"
        );
        
        if ($activityRes) {
            while ($row = $activityRes->fetch_assoc()) {
                $activityEvents[] = $row;
            }
        }
        
        return $activityEvents;
    }

    public function getChartData() {
        $violationStats = $this->getViolationStats();
        $accidentStats = $this->getAccidentStats();
        
        $vaChartLabels = [];
        $vaViolationsPerDay = [];
        $vaAccidentsPerDay = [];
        
        $today = new DateTime('today');
        $start = (new DateTime('today'))->modify('-29 days');
        
        $cursor = clone $start;
        while ($cursor <= $today) {
            $key = $cursor->format('Y-m-d');
            $vaChartLabels[] = $cursor->format('M j');
            $vaViolationsPerDay[] = $violationStats['byDay'][$key] ?? 0;
            $vaAccidentsPerDay[] = $accidentStats['byDay'][$key] ?? 0;
            $cursor->modify('+1 day');
        }
        
        return [
            'labels' => $vaChartLabels,
            'violations' => $vaViolationsPerDay,
            'accidents' => $vaAccidentsPerDay
        ];
    }
}
?>
