"te<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Include all controllers
require_once '../app/Controllers/DriverController.php';
require_once '../app/Controllers/VehicleController.php';
require_once '../app/Controllers/ViolationController.php';
require_once '../app/Controllers/AccidentController.php';
require_once '../app/Controllers/PaymentController.php';
require_once '../app/Controllers/DashboardController.php';

// Initialize controllers
$driverController = new DriverController();
$vehicleController = new VehicleController();
$violationController = new ViolationController();
$accidentController = new AccidentController();
$paymentController = new PaymentController();
$dashboardController = new DashboardController();

// Handle CRUD operations
$driverController->store();
$driverController->update();
$driverController->delete();
$driverEditData = $driverController->edit();

$vehicleController->store();
$vehicleController->update();
$vehicleController->delete();
$vehicleEditData = $vehicleController->edit();

$violationController->store();
$violationController->delete();

$accidentController->store();
$accidentController->delete();

$paymentController->store();
$paymentController->delete();

// Get data for dashboard
$driverData = $driverController->index();
$vehicleData = $vehicleController->index();
$violationData = $violationController->index();
$accidentData = $accidentController->index();
$paymentData = $paymentController->index();

// Get dashboard statistics
$data = [
    'drivers' => $driverData,
    'vehicles' => $vehicleData,
    'violations' => $violationData,
    'accidents' => $accidentData,
    'payments' => $paymentData,
    'systemCounts' => $dashboardController->getSystemCounts(),
    'chartData' => $dashboardController->getChartData(),
    'activityEvents' => $dashboardController->getActivityEvents(),
    'violationStats' => $dashboardController->getViolationStats(),
    'accidentStats' => $dashboardController->getAccidentStats(),
];

// Show dashboard view
require_once '../app/Views/dashboard/index.php';
?>
