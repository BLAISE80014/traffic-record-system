<?php
include("db.php");
//Driver Section

/* ================= DRIVER SECTION ================= */

/* DELETE DRIVER */
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM drivers WHERE id=$id");
    header("Location: dashboard.php#driver");
    exit();
}

/* EDIT DRIVER */
$editData = null;

if(isset($_GET['edit'])){
    $id = intval($_GET['edit']);
    $res = $conn->query("SELECT * FROM drivers WHERE id=$id");

    if($res && $res->num_rows > 0){
        $editData = $res->fetch_assoc();
    }
}

/* SAVE DRIVER */
if(isset($_POST['save'])){
    $name = $_POST['name'];
    $license = $_POST['license'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $phone = $_POST['phone'];

    $conn->query("INSERT INTO drivers (name, license_number, dob, gender, phone)
    VALUES ('$name','$license','$dob','$gender','$phone')");

    header("Location: dashboard.php#driver");
    exit();
}

/* UPDATE DRIVER */
if(isset($_POST['update'])){
    $id = intval($_POST['id']);

    $conn->query("UPDATE drivers SET
        name='{$_POST['name']}',
        license_number='{$_POST['license']}',
        dob='{$_POST['dob']}',
        gender='{$_POST['gender']}',
        phone='{$_POST['phone']}'
        WHERE id=$id
    ");

    header("Location: dashboard.php#driver");
    exit();
}

/* SEARCH DRIVER */
$search = $_GET['search'] ?? '';

if($search != ''){
    $driverResult = $conn->query("
        SELECT * FROM drivers 
        WHERE name LIKE '%$search%' 
        OR license_number LIKE '%$search%'
        ORDER BY id DESC
    ");
} else {
    $driverResult = $conn->query("SELECT * FROM drivers ORDER BY id DESC");
}

//Vehicle Section
/* ---------- DELETE ---------- */
if(isset($_GET['v_delete'])){
    $id = $_GET['v_delete'];
    $conn->query("DELETE FROM vehicles WHERE id=$id");
    header("Location: dashboard.php#vehicle");
    exit();
}

/* ---------- EDIT ---------- */
$v_editData = null;
if(isset($_GET['v_edit'])){
    $id = $_GET['v_edit'];
    $res = $conn->query("SELECT * FROM vehicles WHERE id=$id");
    if($res && $res->num_rows > 0){
        $v_editData = $res->fetch_assoc();
    }
}

/* ---------- SAVE ---------- */
if(isset($_POST['saving'])){
    $conn->query("INSERT INTO vehicles (plate_number, type, model)
    VALUES ('$_POST[plate]','$_POST[type]','$_POST[model]')");
    
    header("Location: dashboard.php#vehicle");
    exit();
}

/* ---------- UPDATE ---------- */
if(isset($_POST['v_update'])){
    $id = $_POST['id'];

    $conn->query("UPDATE vehicles SET
        plate_number='$_POST[plate]',
        type='$_POST[type]',
        model='$_POST[model]'
        WHERE id=$id
    ");

    header("Location: dashboard.php#vehicle");
    exit();
}

//Violation Section
/* ---------- ADD ---------- */
if(isset($_POST['add'])){
    $driver_id = $_POST['driver_id'];
    $type = $_POST['type'];
    $date = $_POST['date'];

    $conn->query("INSERT INTO violations (driver_id, type, date)
    VALUES ('$driver_id','$type','$date')");
}

/* ---------- DELETE ---------- */
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $conn->query("DELETE FROM violations WHERE id=$id");
}

/* ---------- EDIT ---------- */
$edit = null;
if(isset($_GET['edit'])){
    $id = $_GET['edit'];
    $res = $conn->query("SELECT * FROM violations WHERE id=$id");
    $edit = $res->fetch_assoc();
}

/* ---------- UPDATE ---------- */
if(isset($_POST['update'])){
    $id = $_POST['id'];

    $conn->query("UPDATE violations SET
        driver_id='$_POST[driver_id]',
        type='$_POST[type]',
        date='$_POST[date]'
        WHERE id=$id
    ");
}

/* ---------- DATA ---------- */
$result = $conn->query("SELECT * FROM violations");

// Dashboard chart data (last 30 days): violations + accidents per day
$vaChartLabels = [];
$vaViolationsPerDay = [];
$vaAccidentsPerDay = [];
$violationLast30Total = 0;
$accidentLast30Total = 0;

$today = new DateTime('today');
$start = (new DateTime('today'))->modify('-29 days');

$violationsByDay = [];
$violDayRes = $conn->query("
    SELECT DATE(`date`) AS d, COUNT(*) AS total
    FROM violations
    WHERE `date` >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
    GROUP BY DATE(`date`)
");
if ($violDayRes) {
    while ($row = $violDayRes->fetch_assoc()) {
        $violationsByDay[$row['d']] = (int) $row['total'];
        $violationLast30Total += (int) $row['total'];
    }
}

$accidentsByDay = [];
$accDayRes = $conn->query("
    SELECT DATE(`date`) AS d, COUNT(*) AS total
    FROM accidents
    WHERE `date` >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
    GROUP BY DATE(`date`)
");
if ($accDayRes) {
    while ($row = $accDayRes->fetch_assoc()) {
        $accidentsByDay[$row['d']] = (int) $row['total'];
        $accidentLast30Total += (int) $row['total'];
    }
}

$cursor = clone $start;
while ($cursor <= $today) {
    $key = $cursor->format('Y-m-d');
    $vaChartLabels[] = $cursor->format('M j');
    $vaViolationsPerDay[] = $violationsByDay[$key] ?? 0;
    $vaAccidentsPerDay[] = $accidentsByDay[$key] ?? 0;
    $cursor->modify('+1 day');
}

// System overview counts (for report chart)
$systemCounts = [
    'Drivers' => 0,
    'Vehicles' => 0,
    'Violations' => 0,
    'Accidents' => 0,
    'Payments' => 0,
];
$driversTotalRes = $conn->query("SELECT COUNT(*) AS total FROM drivers");
if ($driversTotalRes) $systemCounts['Drivers'] = (int) $driversTotalRes->fetch_assoc()['total'];
$vehiclesTotalRes = $conn->query("SELECT COUNT(*) AS total FROM vehicles");
if ($vehiclesTotalRes) $systemCounts['Vehicles'] = (int) $vehiclesTotalRes->fetch_assoc()['total'];
$violationsTotalRes = $conn->query("SELECT COUNT(*) AS total FROM violations");
if ($violationsTotalRes) $systemCounts['Violations'] = (int) $violationsTotalRes->fetch_assoc()['total'];
$accidentsTotalRes = $conn->query("SELECT COUNT(*) AS total FROM accidents");
if ($accidentsTotalRes) $systemCounts['Accidents'] = (int) $accidentsTotalRes->fetch_assoc()['total'];
$paymentsTotalRes = $conn->query("SELECT COUNT(*) AS total FROM payments");
if ($paymentsTotalRes) $systemCounts['Payments'] = (int) $paymentsTotalRes->fetch_assoc()['total'];

//Accident Section
if(isset($_POST['save_accident'])){

  $driver_id = $_POST['driver_id'];
  $vehicle_id = $_POST['vehicle_id'];
  $location = $_POST['location'];
  $date = $_POST['date'];
  $description = $_POST['description'];

  $sql = "INSERT INTO accidents (driver_id, vehicle_id, location, date, description)
          VALUES ('$driver_id','$vehicle_id','$location','$date','$description')";

  if($conn->query($sql)){
    echo "Accident saved!";
  } else {
    echo "Error: " . $conn->error;
  }
}

//Payment Section
if(isset($_POST['save_payment'])){

  $driver_id = $_POST['driver_id'];
  $amount = $_POST['amount'];

  $sql = "INSERT INTO payments (driver_id, amount)
          VALUES ('$driver_id','$amount')";

  if($conn->query($sql)){
    echo "Payment saved!";
  } else {
    echo "Error: " . $conn->error;
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Traffic Record System | Modern Dashboard</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<style>
/* Custom Styles - Only styling improvements, no functionality removed */
.menu-item {
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}
.menu-item:hover {
  transform: translateX(5px);
  background-color: #1e293b !important;
}
.menu-item::before {
  content: '';
  position: absolute;
  left: 0;
  top: 0;
  height: 100%;
  width: 3px;
  background-color: #3b82f6;
  transform: scaleY(0);
  transition: transform 0.3s ease;
}
.menu-item:hover::before {
  transform: scaleY(1);
}

/* Card hover effects */
.stat-card {
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  cursor: pointer;
}
.stat-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 20px 25px -12px rgba(0, 0, 0, 0.15);
}

/* Table styling */
.table-container {
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}
table {
  width: 100%;
  border-collapse: separate;
  border-spacing: 0;
}
th {
  background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
  font-weight: 600;
  text-transform: uppercase;
  font-size: 0.75rem;
  letter-spacing: 0.05em;
  color: #475569;
  padding: 16px 20px;
  border-bottom: 2px solid #e2e8f0;
}
td {
  padding: 14px 20px;
  border-bottom: 1px solid #f1f5f9;
  transition: background-color 0.2s ease;
}
tr:hover td {
  background-color: #f8fafc;
}

/* Button styles */
.btn-primary {
  background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
  transition: all 0.3s ease;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}
.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3);
}
.btn-primary:active {
  transform: translateY(0);
}

/* Modal animation */
.modal {
  animation: fadeIn 0.3s ease-out;
}
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: scale(0.95);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}

/* Input field focus effects */
input:focus, select:focus, textarea:focus {
  outline: none;
  ring: 2px solid #3b82f6;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
  transition: all 0.2s;
}

/* Scrollbar styling */
::-webkit-scrollbar {
  width: 8px;
  height: 8px;
}
::-webkit-scrollbar-track {
  background: #e2e8f0;
  border-radius: 10px;
}
::-webkit-scrollbar-thumb {
  background: #94a3b8;
  border-radius: 10px;
}
::-webkit-scrollbar-thumb:hover {
  background: #64748b;
}

/* Sidebar active state */
.menu-item.active {
  background-color: #1e293b !important;
  border-left: 3px solid #3b82f6;
}

/* Dashboard card icons */
.icon-bg {
  background: linear-gradient(135deg, rgba(59,130,246,0.1) 0%, rgba(37,99,235,0.05) 100%);
  border-radius: 12px;
  padding: 8px;
}

/* Badge styles */
.badge {
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 600;
  letter-spacing: 0.025em;
}
.badge-success {
  background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
  color: #166534;
}
.badge-warning {
  background: linear-gradient(135deg, #fef9c3 0%, #fef08a 100%);
  color: #854d0e;
}
.badge-danger {
  background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
  color: #991b1b;
}
.badge-info {
  background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
  color: #1e40af;
}

/* Activity timeline */
.activity-item {
  transition: all 0.2s ease;
  border-left: 3px solid transparent;
}
.activity-item:hover {
  background-color: #f8fafc;
  border-left-color: #3b82f6;
  transform: translateX(4px);
}

/* Form container */
.form-container {
  background: white;
  border-radius: 20px;
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

/* Progress bar animation */
@keyframes slideIn {
  from {
    width: 0;
  }
  to {
    width: var(--target-width);
  }
}
.progress-bar {
  animation: slideIn 1s ease-out;
}
</style>
</head>
<body class="bg-gradient-to-br from-gray-50 via-gray-100 to-gray-50 flex">

<!-- SIDEBAR with enhanced styling -->
<div class="w-64 bg-gradient-to-b from-slate-900 to-slate-800 text-white flex flex-col justify-between p-5 fixed h-full shadow-2xl">

  <div>

    <div class="flex items-center gap-3 mb-8 pb-4 border-b border-slate-700">
      <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
        <i class="fa-solid fa-traffic-light text-white text-xl"></i>
      </div>
      <div>
        <h2 class="text-xl font-bold tracking-tight">Traffic System</h2>
        <p class="text-xs text-slate-400">Fleet Management</p>
      </div>
    </div>

    <div class="flex flex-col gap-2">
      <a href="#" onclick="showSection('dashboard')" class="menu-item flex items-center gap-3 p-3 rounded-xl hover:bg-slate-800 transition-all duration-300">
        <i class="fa-solid fa-house text-blue-400 w-5"></i> 
        <span class="font-medium">Dashboard</span>
      </a>
      <a href="#" onclick="showSection('driver')" class="menu-item flex items-center gap-3 p-3 rounded-xl hover:bg-slate-800 transition-all duration-300">
        <i class="fa-solid fa-circle-user text-cyan-400 w-5"></i> 
        <span class="font-medium">Drivers</span>
      </a>
      <a href="#" onclick="showSection('vehicle')" class="menu-item flex items-center gap-3 p-3 rounded-xl hover:bg-slate-800 transition-all duration-300">
        <i class="fa-solid fa-car text-green-400 w-5"></i> 
        <span class="font-medium">Vehicles</span>
      </a>
      <a href="#" onclick="showSection('violation')" class="menu-item flex items-center gap-3 p-3 rounded-xl hover:bg-slate-800 transition-all duration-300">
        <i class="fa-solid fa-gavel text-yellow-400 w-5"></i> 
        <span class="font-medium">Violations</span>
      </a>
      <a href="#" onclick="showSection('accident')" class="menu-item flex items-center gap-3 p-3 rounded-xl hover:bg-slate-800 transition-all duration-300">
        <i class="fa-solid fa-car-burst text-red-400 w-5"></i> 
        <span class="font-medium">Accidents</span>
      </a>
      <a href="#" onclick="showSection('payment')" class="menu-item flex items-center gap-3 p-3 rounded-xl hover:bg-slate-800 transition-all duration-300">
        <i class="fa-solid fa-credit-card text-purple-400 w-5"></i> 
        <span class="font-medium">Payments</span>
      </a>

      <a href="#" onclick="showSection('report')" 
   class="menu-item flex items-center gap-3 p-3 rounded-xl hover:bg-slate-800 transition-all duration-300">

  <i class="fa-solid fa-chart-bar text-blue-400 w-5"></i> 

  <span class="font-medium">Reports</span>

</a>

      <a href="#" onclick="logout()" class="menu-item bg-gradient-to-r from-red-600 to-red-700 p-3 rounded-xl mt-6 flex items-center gap-3 shadow-lg hover:shadow-red-500/20 transition-all duration-300">
        <i class="fa-solid fa-right-from-bracket"></i>
        <span class="font-medium">Logout</span>
      </a>
    </div>
  </div>

  <div class="text-center text-slate-500 text-xs pt-4 border-t border-slate-700 mt-4">
    <i class="fa-regular fa-copyright"></i> 2026 Traffic System
  </div>
</div>


<!-- MAIN CONTENT with enhanced styling -->
<div class="flex-1 p-8 ml-[17rem]">

<!-- DASHBOARD -->
<div id="dashboard" class="section">

  <div class="mb-8">
    <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Dashboard</h1>
    <p class="text-gray-500 mt-1">Welcome back! Here's what's happening with your fleet today.</p>
  </div>

  <!-- CARDS with hover effects -->
  <div class="grid grid-cols-4 gap-6 mb-8">
    <div class="stat-card bg-gradient-to-br from-white to-gray-50 p-6 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 border border-gray-100">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-gray-500 text-sm font-semibold uppercase tracking-wide">Drivers</p>
          <p class="text-3xl font-bold text-gray-800 mt-2" id="driverCount">0</p>
        </div>
        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
          <i class="fa-solid fa-users text-blue-600 text-xl"></i>
        </div>
      </div>
      <div class="mt-3 text-xs text-green-600"><i class="fa-solid fa-arrow-up"></i> +12% this month</div>
    </div>

    <div class="stat-card bg-gradient-to-br from-white to-gray-50 p-6 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 border border-gray-100">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-gray-500 text-sm font-semibold uppercase tracking-wide">Vehicles</p>
          <p class="text-3xl font-bold text-gray-800 mt-2" id="vehicleCount">0</p>
        </div>
        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
          <i class="fa-solid fa-car text-green-600 text-xl"></i>
        </div>
      </div>
      <div class="mt-3 text-xs text-green-600"><i class="fa-solid fa-chart-line"></i> 85% active</div>
    </div>

    <div class="stat-card bg-gradient-to-br from-white to-gray-50 p-6 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 border border-gray-100">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-gray-500 text-sm font-semibold uppercase tracking-wide">Violations</p>
          <p class="text-3xl font-bold text-gray-800 mt-2" id="violationCount">0</p>
        </div>
        <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
          <i class="fa-solid fa-gavel text-yellow-600 text-xl"></i>
        </div>
      </div>
      <div class="mt-3 text-xs text-red-600"><i class="fa-solid fa-exclamation-triangle"></i> 3 pending</div>
    </div>

    <div class="stat-card bg-gradient-to-br from-white to-gray-50 p-6 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 border border-gray-100">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-gray-500 text-sm font-semibold uppercase tracking-wide">Accidents</p>
          <p class="text-3xl font-bold text-gray-800 mt-2" id="accidentCount">0</p>
        </div>
        <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
          <i class="fa-solid fa-car-crash text-red-600 text-xl"></i>
        </div>
      </div>
      <div class="mt-3 text-xs text-orange-600"><i class="fa-solid fa-calendar-week"></i> This quarter</div>
    </div>
  </div>

  <!-- SYSTEM INFO & RECENT ACTIVITY side by side -->
  <div class="grid grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
      <div class="flex items-center gap-3 mb-4">
        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
          <i class="fa-solid fa-circle-info text-white"></i>
        </div>
        <h3 class="text-lg font-bold text-gray-800">System Information</h3>
      </div>
      <div class="space-y-3">
        <div class="flex justify-between items-center py-2 border-b border-gray-100">
          <span class="text-gray-600"><i class="fa-regular fa-user mr-2"></i>Logged in:</span>
          <span class="font-semibold text-gray-800">Blaise</span>
        </div>
        <div class="flex justify-between items-center py-2 border-b border-gray-100">
          <span class="text-gray-600"><i class="fa-regular fa-circle-check mr-2"></i>Status:</span>
          <span class="badge badge-success">Active</span>
        </div>
        <div class="flex justify-between items-center py-2 border-b border-gray-100">
          <span class="text-gray-600"><i class="fa-regular fa-envelope mr-2"></i>Email:</span>
          <span class="font-semibold text-gray-800">blaisehirwanshuti@gmail.com</span>
        </div>
        <div class="flex justify-between items-center py-2">
          <span class="text-gray-600"><i class="fa-regular fa-phone mr-2"></i>Phone:</span>
          <span class="font-semibold text-gray-800">+250 796 261 912</span>
        </div>
      </div>
    </div>

    <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
      <div class="flex items-center gap-3 mb-4">
        <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center">
          <i class="fa-regular fa-clock text-white"></i>
        </div>
        <h3 class="text-lg font-bold text-gray-800">Recent Activity</h3>
      </div>
      <div id="activityList" class="space-y-3 max-h-64 overflow-y-auto">
        <div class="text-center text-gray-400 py-4">No recent activity yet</div>
      </div>
    </div>
  </div>

  <!-- VIOLATIONS STATS -->
  <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
    <div class="flex items-center gap-3 mb-4">
      <div class="w-10 h-10 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl flex items-center justify-center">
        <i class="fa-solid fa-chart-simple text-white"></i>
      </div>
      <h3 class="text-lg font-bold text-gray-800">Violation Statistics</h3>
    </div>
    <div class="grid grid-cols-2 gap-6">
      <div>
        <div class="h-56">
          <canvas id="vaBarChart" aria-label="Violations and accidents (last 30 days)" role="img"></canvas>
        </div>
        <?php if ((int)$violationLast30Total === 0 && (int)$accidentLast30Total === 0) { ?>
          <p class="text-sm text-gray-500 mt-3">No violations or accidents recorded in the last 30 days.</p>
        <?php } ?>
      </div>
      <div class="flex items-center justify-center">
        <div class="text-center">
          <div class="w-32 h-32 rounded-full bg-gradient-to-br from-blue-100 to-blue-50 flex items-center justify-center mx-auto">
            <div class="text-center">
              <div class="text-3xl font-bold text-blue-600" id="vaTotal30"><?= (int)$violationLast30Total + (int)$accidentLast30Total ?></div>
              <div class="text-xs text-gray-500">Total Cases</div>
            </div>
          </div>
          <p class="text-sm text-gray-600 mt-3">Last 30 days</p>
        </div>
      </div>
    </div>
  </div>


  

  <div class="text-center text-gray-400 text-sm mt-8 pb-4">
    <i class="fa-regular fa-copyright"></i> Traffic System Dashboard — Real-time Monitoring
  </div>
</div>

<!-- DRIVER SECTION -->
<div id="driver" class="section p-6">

  <!-- HEADER -->
  <div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold">Drivers</h2>

    <button onclick="toggleForm('driverForm')" 
      class="bg-blue-600 text-white px-4 py-2 rounded">
      Add Driver
    </button>
  </div>

  <!-- SEARCH -->
  <form method="GET" class="mb-4 flex gap-2">
    <input type="text" name="search"
      value="<?= $_GET['search'] ?? '' ?>"
      placeholder="Search driver..."
      class="border p-2 w-full rounded">

    <button class="bg-blue-500 text-white px-4 rounded">Search</button>

    <a href="dashboard.php#driver" class="bg-gray-500 text-white px-4 rounded">Reset</a>
  </form>

  <!-- FORM -->
  <div id="driverForm" class="hidden bg-white p-4 rounded mb-6">

    <form method="POST">

      <input name="name"
        value="<?= $editData['name'] ?? '' ?>"
        placeholder="Name"
        class="border p-2 w-full mb-2">

      <input name="license"
        value="<?= $editData['license_number'] ?? '' ?>"
        placeholder="License"
        class="border p-2 w-full mb-2">

      <input type="date" name="dob"
        value="<?= $editData['dob'] ?? '' ?>"
        class="border p-2 w-full mb-2">

      <input name="gender"
        value="<?= $editData['gender'] ?? '' ?>"
        placeholder="Gender"
        class="border p-2 w-full mb-2">

      <input name="phone"
        value="<?= $editData['phone'] ?? '' ?>"
        placeholder="Phone"
        class="border p-2 w-full mb-2">

      <?php if($editData){ ?>
        <input type="hidden" name="id" value="<?= $editData['id'] ?>">
        <button name="update" class="bg-yellow-500 text-white w-full p-2 rounded">
          Update Driver
        </button>
      <?php } else { ?>
        <button name="save" class="bg-green-600 text-white w-full p-2 rounded">
          Save Driver
        </button>
      <?php } ?>

    </form>

  </div>

  <!-- TABLE -->
  <div class="bg-white p-4 rounded">

    <table class="w-full border">
      <thead>
        <tr>
          <th>Name</th>
          <th>License</th>
          <th>DOB</th>
          <th>Gender</th>
          <th>Phone</th>
          <th>Actions</th>
        </tr>
      </thead>

      <tbody>
<?php while($row = $driverResult->fetch_assoc()){ ?>
<tr>
  <td><?= $row['name'] ?></td>
  <td><?= $row['license_number'] ?></td>
  <td><?= $row['dob'] ?></td>
  <td><?= $row['gender'] ?></td>
  <td><?= $row['phone'] ?></td>

  <td class="flex gap-2">
    <a href="?edit=<?= $row['id'] ?>#driver"
       class="bg-blue-500 text-white px-2 py-1 rounded">
      Edit
    </a>

    <a href="?delete=<?= $row['id'] ?>#driver"
       onclick="return confirm('Delete driver?')"
       class="bg-red-500 text-white px-2 py-1 rounded">
      Delete
    </a>
  </td>
</tr>
<?php } ?>
</tbody>

    </table>

  </div>

</div>
<!-- VEHICLE SECTION -->
<div id="vehicle" class="section hidden p-6">

  <!-- HEADER -->
  <div class="flex justify-between items-center mb-6">
    <div>
      <h2 class="text-2xl font-bold text-gray-800">Vehicles</h2>
      <p class="text-gray-500 text-sm">Track your fleet</p>
    </div>

    <button onclick="toggleForm('vehicleForm')" 
      class="bg-blue-600 text-white px-4 py-2 rounded">
      Add Vehicle
    </button>
  </div>

  <!-- FORM -->
  <div id="vehicleForm" class="hidden bg-white p-6 rounded-xl mb-6">
    <form method="POST">

      <input name="plate" value="<?= $v_editData['plate_number'] ?? '' ?>" 
        class="border p-3 mb-3 w-full rounded" placeholder="Plate Number">

      <input name="type" value="<?= $v_editData['type'] ?? '' ?>" 
        class="border p-3 mb-3 w-full rounded" placeholder="Vehicle Type">

      <input name="model" value="<?= $v_editData['model'] ?? '' ?>" 
        class="border p-3 mb-3 w-full rounded" placeholder="Model">

      <?php if($v_editData){ ?>
        <input type="hidden" name="id" value="<?= $v_editData['id'] ?>">
        <button type="submit" name="v_update" 
          class="bg-yellow-500 text-white w-full p-2 rounded">
          Update Vehicle
        </button>
      <?php } else { ?>
        <button type="submit" name="saving" 
          class="bg-green-600 text-white w-full p-2 rounded">
          Save Vehicle
        </button>
      <?php } ?>

    </form>
  </div>

  <!-- TABLE -->
  <div class="bg-white rounded-xl p-4">
    <table class="w-full">
      <thead>
        <tr class="border-b">
          <th>ID</th>
          <th>Plate</th>
          <th>Type</th>
          <th>Model</th>
          <th>Actions</th>
        </tr>
      </thead>

      <tbody>
      <?php
      $result = $conn->query("SELECT * FROM vehicles");

      while($row = $result->fetch_assoc()){
      ?>
        <tr class="border-b">
          <td><?= $row['id'] ?></td>
          <td><?= $row['plate_number'] ?></td>
          <td><?= $row['type'] ?></td>
          <td><?= $row['model'] ?></td>

          <td class="flex gap-2">
            <!-- EDIT -->
            <a href="?v_edit=<?= $row['id'] ?>#vehicle" 
              class="bg-blue-500 text-white px-2 py-1 rounded">
              Edit
            </a>

            <!-- DELETE -->
            <a href="?v_delete=<?= $row['id'] ?>#vehicle" 
              onclick="return confirm('Delete this vehicle?')" 
              class="bg-red-500 text-white px-2 py-1 rounded">
              Delete
            </a>
          </td>
        </tr>
      <?php } ?>
      </tbody>

    </table>
  </div>

</div>
<!-- VIOLATION SECTION -->
<div id="violation" class="section hidden">
  <div class="flex justify-between items-center mb-6">
    <div><h2 class="text-2xl font-bold text-gray-800">Violations</h2><p class="text-gray-500 text-sm">Record traffic offenses</p></div>
    <button onclick="toggleForm('violationForm')" class="btn-primary text-white px-6 py-2.5 rounded-xl flex items-center gap-2 shadow-lg"><i class="fa-solid fa-plus"></i> Add Violation</button>
  </div>
       <form method="POST"> 
  <div id="violationForm" class="hidden mb-6 form-container p-6 bg-white rounded-2xl">

    <input id="vi_driver" name="driver_id" class="border border-gray-200 rounded-xl p-3 mb-3 w-full" placeholder="Driver ID">
    <input id="vi_type" name="type" class="border border-gray-200 rounded-xl p-3 mb-3 w-full" placeholder="Violation Type">
    <input type="date" id="vi_date" name="date" class="border border-gray-200 rounded-xl p-3 mb-3 w-full" placeholder="Date">
    <button type="submit" name="add" class="bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-2.5 rounded-xl w-full font-semibold">Save Violation</button>

  </div>
  </form>
  <div class="table-container bg-white rounded-2xl">
    <table class="w-full">
      <thead>
        <tr>
          <th>Driver</th>
          <th>Type</th>
          <th>Date</th>
        </tr>
      </thead>
      <tbody id="violationTable">
      </tbody>
      <?php
      $result = $conn->query("SELECT * FROM violations");
      
      while($row = $result->fetch_assoc()){
        echo "<tr>
              <td>{$row['driver_id']}</td>
              <td>{$row['type']}</td>
              <td>{$row['date']}</td>
        </tr>";
      }
      ?>
    </table>
  </div>
</div>

<!-- ACCIDENT SECTION -->
<div id="accident" class="section hidden">
  <div class="flex justify-between items-center mb-6">
    <div><h2 class="text-2xl font-bold text-gray-800">Accidents</h2><p class="text-gray-500 text-sm">Incident reports</p></div>
    <button onclick="toggleForm('accidentForm')" class="btn-primary text-white px-6 py-2.5 rounded-xl flex items-center gap-2 shadow-lg"><i class="fa-solid fa-plus"></i> Report Accident</button>
  </div>
<form method="POST">
  <div id="accidentForm" class="hidden mb-6 form-container p-6 bg-white rounded-2xl">

    <input name="driver_id" class="border p-3 mb-3 w-full rounded-xl" placeholder="Driver ID">

    <input name="vehicle_id" class="border p-3 mb-3 w-full rounded-xl" placeholder="Vehicle ID">

    <input name="location" class="border p-3 mb-3 w-full rounded-xl" placeholder="Location">

    <input type="date" name="date" class="border p-3 mb-3 w-full rounded-xl">

    <textarea name="description" class="border p-3 mb-3 w-full rounded-xl" placeholder="Description"></textarea>

    <button type="submit" name="save_accident"
      class="bg-green-600 text-white px-6 py-2 rounded-xl w-full font-semibold">
      Save Accident
    </button>

  </div>
</form>
  <div class="table-container bg-white rounded-2xl">
    <table class="w-full">
      <thead>
        <tr>
          <th>ID</th>
          <th>Driver</th>
          <th>Vehicle</th>
          <th>Location</th>
          <th>Date</th>
          <th>Description</th>
        </tr>
      </thead>
      <tbody id="accidentTable">
      </tbody>
      <tbody>
<?php
$result = $conn->query("SELECT * FROM accidents");

while($row = $result->fetch_assoc()){
  echo "<tr>
      <td>{$row['id']}</td>
      <td>{$row['driver_id']}</td>
      <td>{$row['vehicle_id']}</td>
      <td>{$row['location']}</td>
      <td>{$row['date']}</td>
      <td>{$row['description']}</td>
  </tr>";
}
?>
</tbody>
    </table>
  </div>
</div>

<!-- PAYMENT SECTION -->
<div id="payment" class="section hidden">
  <div class="flex justify-between items-center mb-6">
    <div><h2 class="text-2xl font-bold text-gray-800">Payments</h2><p class="text-gray-500 text-sm">Financial transactions</p></div>
    <button onclick="toggleForm('paymentForm')" class="btn-primary text-white px-6 py-2.5 rounded-xl flex items-center gap-2 shadow-lg"><i class="fa-solid fa-plus"></i> New Payment</button>
  </div>
 <form method="POST">
  <div id="paymentForm" class="hidden mb-6 form-container p-6 bg-white rounded-2xl">

    <input name="driver_id" class="border p-3 mb-3 w-full rounded-xl" placeholder="Driver ID">

    <input name="amount" class="border p-3 mb-3 w-full rounded-xl" placeholder="Amount">

    <button type="submit" name="save_payment"
      class="bg-green-600 text-white px-6 py-2 rounded-xl w-full font-semibold">
      Save Payment
    </button>

  </div>
</form>
  <div class="table-container bg-white rounded-2xl">
    <table class="w-full">
      <thead>
        <tr>
          <th>Driver</th>
          <th>Amount</th>
        </tr>
      </thead>
      <tbody id="paymentTable">
      </tbody>
      <?php
$result = $conn->query("SELECT * FROM payments");

while($row = $result->fetch_assoc()){
  echo "<tr>
      <td>{$row['driver_id']}</td>
      <td>{$row['amount']}</td>
  </tr>";
}
?>
    </table>
  </div>
</div>
<!-- REPORT SECTION -->
<div id="report" class="section hidden p-6">

  <h2 class="text-3xl font-bold text-gray-800 mb-6">System Reports</h2>
  <p class="text-gray-500 mb-6">Overview of all traffic system records</p>

  <!-- STATS CARDS -->
  <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">

    <!-- Drivers -->
    <div class="bg-white rounded-2xl shadow p-5 text-center">
      <h3 class="text-gray-500">Drivers</h3>
      <p class="text-3xl font-bold text-blue-600">
        <?php
          echo $conn->query("SELECT COUNT(*) as total FROM drivers")->fetch_assoc()['total'];
        ?>
      </p>
    </div>

    <!-- Vehicles -->
    <div class="bg-white rounded-2xl shadow p-5 text-center">
      <h3 class="text-gray-500">Vehicles</h3>
      <p class="text-3xl font-bold text-green-600">
        <?php
          echo $conn->query("SELECT COUNT(*) as total FROM vehicles")->fetch_assoc()['total'];
        ?>
      </p>
    </div>

    <!-- Violations -->
    <div class="bg-white rounded-2xl shadow p-5 text-center">
      <h3 class="text-gray-500">Violations</h3>
      <p class="text-3xl font-bold text-red-600">
        <?php
          echo $conn->query("SELECT COUNT(*) as total FROM violations")->fetch_assoc()['total'];
        ?>
      </p>
    </div>

    <!-- Accidents -->
    <div class="bg-white rounded-2xl shadow p-5 text-center">
      <h3 class="text-gray-500">Accidents</h3>
      <p class="text-3xl font-bold text-yellow-600">
        <?php
          echo $conn->query("SELECT COUNT(*) as total FROM accidents")->fetch_assoc()['total'];
        ?>
      </p>
    </div>

    <!-- Payments -->
    <div class="bg-white rounded-2xl shadow p-5 text-center">
      <h3 class="text-gray-500">Payments</h3>
      <p class="text-3xl font-bold text-purple-600">
        <?php
          echo $conn->query("SELECT COUNT(*) as total FROM payments")->fetch_assoc()['total'];
        ?>
      </p>
    </div>

  </div>

  <!-- SYSTEM OVERVIEW BAR CHART -->
  <div class="mt-8 bg-white p-6 rounded-2xl shadow">
    <div class="flex items-center justify-between mb-4">
      <h3 class="text-xl font-bold text-gray-800">System Overview (Totals)</h3>
      <span class="text-sm text-gray-500">All time</span>
    </div>
    <div class="h-72">
      <canvas id="systemOverviewBarChart" aria-label="System totals overview" role="img"></canvas>
    </div>
  </div>

  <!-- SIMPLE SUMMARY BOX -->
  <div class="mt-8 bg-white p-6 rounded-2xl shadow">
    <h3 class="text-xl font-bold mb-4">Quick Summary</h3>

    <ul class="space-y-2 text-gray-700">
      <li>✔ Total drivers registered in the system</li>
      <li>✔ Vehicles currently tracked</li>
      <li>✔ Recorded traffic violations</li>
      <li>✔ Accident reports stored</li>
      <li>✔ Payment transactions history</li>
    </ul>
  </div>

</div>
</div>

<!-- ========== ORIGINAL JAVASCRIPT - FULLY INTACT ========== -->
<script>
const vaChartData = {
  labels: <?= json_encode($vaChartLabels, JSON_UNESCAPED_UNICODE) ?>,
  violations: <?= json_encode($vaViolationsPerDay) ?>,
  accidents: <?= json_encode($vaAccidentsPerDay) ?>
};

const systemOverviewChartData = {
  labels: <?= json_encode(array_keys($systemCounts), JSON_UNESCAPED_UNICODE) ?>,
  totals: <?= json_encode(array_values($systemCounts)) ?>
};

function showSection(id){
  document.querySelectorAll('.section').forEach(s=>s.classList.add('hidden'));
  document.getElementById(id).classList.remove('hidden');
}

function toggleForm(id){
  document.getElementById(id).classList.toggle('hidden');
}

function updateDashboard(){
  let driverCountElem = document.getElementById('driverCount');
  let vehicleCountElem = document.getElementById('vehicleCount');
  let violationCountElem = document.getElementById('violationCount');
  let accidentCountElem = document.getElementById('accidentCount');
  
  if(driverCountElem) driverCountElem.innerText = document.getElementById('driverTable').rows.length;
  if(vehicleCountElem) vehicleCountElem.innerText = document.getElementById('vehicleTable').rows.length;
  if(violationCountElem) violationCountElem.innerText = document.getElementById('violationTable').rows.length;
  if(accidentCountElem) accidentCountElem.innerText = document.getElementById('accidentTable').rows.length;
}

function addActivity(text){
  const list=document.getElementById('activityList');
  if(!list) return;
  if(list.children.length===1 && list.children[0].innerText==="No recent activity yet"){
    list.innerHTML="";
  }
  const div=document.createElement('div');
  div.className = "activity-item p-3 rounded-xl border-l-3 transition-all";
  div.innerHTML = `<div class="flex items-center gap-3"><i class="fa-regular fa-bell text-blue-500"></i><span class="text-sm text-gray-700">${text}</span><span class="text-xs text-gray-400 ml-auto">Just now</span></div>`;
  list.prepend(div);
  if(list.children.length > 5) list.removeChild(list.lastChild);
}

function addDriver(){
  let name = document.getElementById('d_name').value;
  let license = document.getElementById('d_license').value;
  let dob = document.getElementById('d_dob').value;
  let gender = document.getElementById('d_gender').value;
  let phone = document.getElementById('d_phone').value;
  
  if(!name) { alert("Please enter driver name"); return; }
  
  let table = document.getElementById('driverTable');
  let row = table.insertRow();
  row.insertCell(0).innerHTML = name;
  row.insertCell(1).innerHTML = license;
  row.insertCell(2).innerHTML = dob;
  row.insertCell(3).innerHTML = gender;
  row.insertCell(4).innerHTML = phone;
  updateDashboard();
  addActivity("Driver added: "+name);
  toggleForm('driverForm');
  document.getElementById('d_name').value = '';
  document.getElementById('d_license').value = '';
  document.getElementById('d_dob').value = '';
  document.getElementById('d_gender').value = '';
  document.getElementById('d_phone').value = '';
}

function addVehicle(){
  let id = document.getElementById('v_id').value;
  let plate = document.getElementById('v_plate').value;
  let type = document.getElementById('v_type').value;
  let model = document.getElementById('v_model').value;
  
  if(!plate) { alert("Please enter plate number"); return; }
  
  let table = document.getElementById('vehicleTable');
  let row = table.insertRow();
  row.insertCell(0).innerHTML = id;
  row.insertCell(1).innerHTML = plate;
  row.insertCell(2).innerHTML = type;
  row.insertCell(3).innerHTML = model;
  updateDashboard();
  addActivity("Vehicle added: "+plate);
  toggleForm('vehicleForm');
}

function addViolation(){
  let driver = document.getElementById('vi_driver').value;
  let type = document.getElementById('vi_type').value;
  let date = document.getElementById('vi_date').value;
  
  if(!driver) { alert("Please enter driver ID"); return; }
  
  let table = document.getElementById('violationTable');
  let row = table.insertRow();
  row.insertCell(0).innerHTML = driver;
  row.insertCell(1).innerHTML = type;
  row.insertCell(2).innerHTML = date;
  updateDashboard();
  addActivity("Violation: "+type);
  toggleForm('violationForm');
}

function addAccident(){
  let id = document.getElementById('a_id').value;
  let driver = document.getElementById('a_driver').value;
  let vehicle = document.getElementById('a_vehicle').value;
  let location = document.getElementById('a_location').value;
  let date = document.getElementById('a_date').value;
  let description = document.getElementById('a_description').value;
  
  let table = document.getElementById('accidentTable');
  let row = table.insertRow();
  row.insertCell(0).innerHTML = id;
  row.insertCell(1).innerHTML = driver;
  row.insertCell(2).innerHTML = vehicle;
  row.insertCell(3).innerHTML = location;
  row.insertCell(4).innerHTML = date;
  row.insertCell(5).innerHTML = description;
  updateDashboard();
  addActivity("Accident at: "+location);
  toggleForm('accidentForm');
}

function addPayment(){
  let driver = document.getElementById('p_driver').value;
  let amount = document.getElementById('p_amount').value;
  
  if(!driver) { alert("Please enter driver ID"); return; }
  
  let table = document.getElementById('paymentTable');
  let row = table.insertRow();
  row.insertCell(0).innerHTML = driver;
  row.insertCell(1).innerHTML = amount;
  addActivity("Payment: "+amount);
  toggleForm('paymentForm');
}

function logout(){
  alert("Logged out successfully");
  window.location.href="index.php";
}

// Initialize with sample data if tables are empty
window.onload = function() {
  // Build violations + accidents bar chart (last 30 days)
  try {
    const canvas = document.getElementById('vaBarChart');
    if (canvas && window.Chart && Array.isArray(vaChartData.labels) && vaChartData.labels.length) {
      new Chart(canvas.getContext('2d'), {
        type: 'bar',
        data: {
          labels: vaChartData.labels,
          datasets: [{
            label: 'Violations',
            data: vaChartData.violations,
            backgroundColor: 'rgba(239, 68, 68, 0.30)',
            borderColor: 'rgba(239, 68, 68, 1)',
            borderWidth: 1,
            borderRadius: 8,
            maxBarThickness: 46
          }, {
            label: 'Accidents',
            data: vaChartData.accidents,
            backgroundColor: 'rgba(245, 158, 11, 0.30)',
            borderColor: 'rgba(245, 158, 11, 1)',
            borderWidth: 1,
            borderRadius: 8,
            maxBarThickness: 46
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { display: true, labels: { color: '#475569' } },
            tooltip: { enabled: true }
          },
          scales: {
            x: { grid: { display: false }, ticks: { color: '#475569' } },
            y: { beginAtZero: true, ticks: { precision: 0, color: '#475569' }, grid: { color: 'rgba(148,163,184,0.35)' } }
          }
        }
      });
    }
  } catch (e) {}

  // Build system overview bar chart (totals)
  try {
    const canvas = document.getElementById('systemOverviewBarChart');
    if (canvas && window.Chart && Array.isArray(systemOverviewChartData.labels) && systemOverviewChartData.labels.length) {
      new Chart(canvas.getContext('2d'), {
        type: 'bar',
        data: {
          labels: systemOverviewChartData.labels,
          datasets: [{
            label: 'Total records',
            data: systemOverviewChartData.totals,
            backgroundColor: [
              'rgba(59, 130, 246, 0.35)',   // Drivers
              'rgba(34, 197, 94, 0.35)',    // Vehicles
              'rgba(239, 68, 68, 0.35)',    // Violations
              'rgba(245, 158, 11, 0.35)',   // Accidents
              'rgba(168, 85, 247, 0.35)'    // Payments
            ],
            borderColor: [
              'rgba(59, 130, 246, 1)',
              'rgba(34, 197, 94, 1)',
              'rgba(239, 68, 68, 1)',
              'rgba(245, 158, 11, 1)',
              'rgba(168, 85, 247, 1)'
            ],
            borderWidth: 1,
            borderRadius: 10,
            maxBarThickness: 60
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: { legend: { display: false } },
          scales: {
            x: { grid: { display: false }, ticks: { color: '#475569' } },
            y: { beginAtZero: true, ticks: { precision: 0, color: '#475569' }, grid: { color: 'rgba(148,163,184,0.35)' } }
          }
        }
      });
    }
  } catch (e) {}

  if(document.getElementById('driverTable').rows.length === 0) {
    let sampleDrivers = [
      ["Blaise Hiranshuti", "DL12345", "1990-05-15", "Male", "+250 796 261 912"],
      ["Alice Uwase", "DL67890", "1988-03-22", "Female", "+250 788 123 456"]
    ];
    sampleDrivers.forEach(d => {
      let row = document.getElementById('driverTable').insertRow();
      row.insertCell(0).innerHTML = d[0];
      row.insertCell(1).innerHTML = d[1];
      row.insertCell(2).innerHTML = d[2];
      row.insertCell(3).innerHTML = d[3];
      row.insertCell(4).innerHTML = d[4];
    });
    updateDashboard();
  }
  showSection('dashboard');
};
function showSection(id){
  document.querySelectorAll('.section').forEach(s => s.classList.add('hidden'));
  document.getElementById(id).classList.remove('hidden');
}

// Auto open section from URL (#driver)
if(window.location.hash){
  showSection(window.location.hash.substring(1));
}



</script>

</body>
</html>