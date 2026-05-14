<?php
if (!isset($data)) $data = [];
$systemCounts = $data['systemCounts'] ?? [];
$chartData = $data['chartData'] ?? [];
$activityEvents = $data['activityEvents'] ?? [];
$violationStats = $data['violationStats'] ?? [];
$accidentStats = $data['accidentStats'] ?? [];
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
.stat-card { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); cursor: pointer; }
.stat-card:hover { transform: translateY(-5px); box-shadow: 0 20px 25px -12px rgba(0, 0, 0, 0.15); }
.menu-item { transition: all 0.3s ease; position: relative; overflow: hidden; }
.menu-item:hover { transform: translateX(5px); background-color: #1e293b !important; }
.table-container { border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
table { width: 100%; border-collapse: separate; border-spacing: 0; }
th { background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); font-weight: 600; padding: 16px 20px; color: #475569; border-bottom: 2px solid #e2e8f0; }
td { padding: 14px 20px; border-bottom: 1px solid #f1f5f9; }
tr:hover td { background-color: #f8fafc; }
</style>
</head>
<body class="bg-gradient-to-br from-gray-50 via-gray-100 to-gray-50 flex">

<!-- SIDEBAR -->
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
      <a href="#" onclick="showSection('dashboard')" class="menu-item flex items-center gap-3 p-3 rounded-xl hover:bg-slate-800">
        <i class="fa-solid fa-house text-blue-400 w-5"></i> <span class="font-medium">Dashboard</span>
      </a>
      <a href="#" onclick="showSection('driver')" class="menu-item flex items-center gap-3 p-3 rounded-xl hover:bg-slate-800">
        <i class="fa-solid fa-circle-user text-cyan-400 w-5"></i> <span class="font-medium">Drivers</span>
      </a>
      <a href="#" onclick="showSection('vehicle')" class="menu-item flex items-center gap-3 p-3 rounded-xl hover:bg-slate-800">
        <i class="fa-solid fa-car text-green-400 w-5"></i> <span class="font-medium">Vehicles</span>
      </a>
      <a href="#" onclick="showSection('violation')" class="menu-item flex items-center gap-3 p-3 rounded-xl hover:bg-slate-800">
        <i class="fa-solid fa-gavel text-yellow-400 w-5"></i> <span class="font-medium">Violations</span>
      </a>
      <a href="#" onclick="showSection('accident')" class="menu-item flex items-center gap-3 p-3 rounded-xl hover:bg-slate-800">
        <i class="fa-solid fa-car-burst text-red-400 w-5"></i> <span class="font-medium">Accidents</span>
      </a>
      <a href="#" onclick="showSection('payment')" class="menu-item flex items-center gap-3 p-3 rounded-xl hover:bg-slate-800">
        <i class="fa-solid fa-credit-card text-purple-400 w-5"></i> <span class="font-medium">Payments</span>
      </a>
      <a href="/TRS/app/Controllers/AuthController.php?logout=true" class="menu-item bg-gradient-to-r from-red-600 to-red-700 p-3 rounded-xl mt-6 flex items-center gap-3 shadow-lg">
        <i class="fa-solid fa-right-from-bracket"></i> <span class="font-medium">Logout</span>
      </a>
    </div>
  </div>
  <div class="text-center text-slate-500 text-xs pt-4 border-t border-slate-700 mt-4">
    <i class="fa-regular fa-copyright"></i> 2026 Traffic System
  </div>
</div>

<!-- MAIN CONTENT -->
<div class="flex-1 p-8 ml-[17rem]">

<!-- DASHBOARD SECTION -->
<div id="dashboard" class="section">
  <div class="mb-8">
    <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">Dashboard</h1>
    <p class="text-gray-500 mt-1">Welcome back! Here's what's happening with your fleet today.</p>
  </div>

  <!-- STATS CARDS -->
  <div class="grid grid-cols-4 gap-6 mb-8">
    <div class="stat-card bg-gradient-to-br from-white to-gray-50 p-6 rounded-2xl shadow-md border border-gray-100">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-gray-500 text-sm font-semibold uppercase tracking-wide">Drivers</p>
          <p class="text-3xl font-bold text-gray-800 mt-2"><?= $systemCounts['Drivers'] ?? 0 ?></p>
        </div>
        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
          <i class="fa-solid fa-users text-blue-600 text-xl"></i>
        </div>
      </div>
    </div>

    <div class="stat-card bg-gradient-to-br from-white to-gray-50 p-6 rounded-2xl shadow-md border border-gray-100">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-gray-500 text-sm font-semibold uppercase tracking-wide">Vehicles</p>
          <p class="text-3xl font-bold text-gray-800 mt-2"><?= $systemCounts['Vehicles'] ?? 0 ?></p>
        </div>
        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
          <i class="fa-solid fa-car text-green-600 text-xl"></i>
        </div>
      </div>
    </div>

    <div class="stat-card bg-gradient-to-br from-white to-gray-50 p-6 rounded-2xl shadow-md border border-gray-100">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-gray-500 text-sm font-semibold uppercase tracking-wide">Violations</p>
          <p class="text-3xl font-bold text-gray-800 mt-2"><?= $systemCounts['Violations'] ?? 0 ?></p>
        </div>
        <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
          <i class="fa-solid fa-gavel text-yellow-600 text-xl"></i>
        </div>
      </div>
    </div>

    <div class="stat-card bg-gradient-to-br from-white to-gray-50 p-6 rounded-2xl shadow-md border border-gray-100">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-gray-500 text-sm font-semibold uppercase tracking-wide">Accidents</p>
          <p class="text-3xl font-bold text-gray-800 mt-2"><?= $systemCounts['Accidents'] ?? 0 ?></p>
        </div>
        <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
          <i class="fa-solid fa-car-crash text-red-600 text-xl"></i>
        </div>
      </div>
    </div>
  </div>

  <!-- SYSTEM INFO & RECENT ACTIVITY -->
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
          <span class="font-semibold text-gray-800"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?></span>
        </div>
        <div class="flex justify-between items-center py-2 border-b border-gray-100">
          <span class="text-gray-600"><i class="fa-regular fa-circle-check mr-2"></i>Status:</span>
          <span class="inline-block px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm">Active</span>
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
        <?php foreach ($activityEvents as $event): ?>
          <div class="p-3 rounded-xl border-l-3 border-slate-200">
            <div class="flex items-center gap-3">
              <i class="fa-regular fa-bell text-blue-500"></i>
              <span class="text-sm text-gray-700"><?= htmlspecialchars($event['description']) ?></span>
              <span class="text-xs text-gray-400 ml-auto"><?= date('M j, Y', strtotime($event['date'])) ?></span>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <!-- CHART -->
  <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
    <h3 class="text-lg font-bold text-gray-800 mb-4">Violation Statistics (Last 30 Days)</h3>
    <canvas id="vaBarChart"></canvas>
  </div>
</div>

<!-- Load other sections from views -->
<?php
require '../Views/drivers/index.php';
require '../Views/vehicles/index.php';
require '../Views/violations/index.php';
require '../Views/accidents/index.php';
require '../Views/payments/index.php';
?>

</div>

<script>
function showSection(section) {
  document.querySelectorAll('.section').forEach(el => el.classList.add('hidden'));
  document.getElementById(section).classList.remove('hidden');
}

function toggleForm(formId) {
  document.getElementById(formId).classList.toggle('hidden');
}

// Initialize chart
const ctx = document.getElementById('vaBarChart')?.getContext('2d');
if (ctx) {
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: <?= json_encode($chartData['labels'] ?? []) ?>,
      datasets: [
        {
          label: 'Violations',
          data: <?= json_encode($chartData['violations'] ?? []) ?>,
          borderColor: '#3b82f6',
          backgroundColor: 'rgba(59, 130, 246, 0.1)',
          tension: 0.3
        },
        {
          label: 'Accidents',
          data: <?= json_encode($chartData['accidents'] ?? []) ?>,
          borderColor: '#ef4444',
          backgroundColor: 'rgba(239, 68, 68, 0.1)',
          tension: 0.3
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: true,
      plugins: { legend: { position: 'bottom' } }
    }
  });
}
</script>

</body>
</html>
