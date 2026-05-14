<?php
if (!isset($data)) $data = [];
$violations = $data['violations'] ?? [];
?>

<div id="violation" class="section hidden">
  <div class="flex justify-between items-center mb-6">
    <div><h2 class="text-2xl font-bold text-gray-800">Violations</h2><p class="text-gray-500 text-sm">Record traffic offenses</p></div>
    <button onclick="toggleForm('violationForm')" class="btn-primary text-white px-6 py-2.5 rounded-xl flex items-center gap-2 shadow-lg"><i class="fa-solid fa-plus"></i> Add Violation</button>
  </div>
  <div id="violationForm" class="hidden mb-6 form-container p-6 bg-white rounded-2xl">

    <input id="vi_driver" name="driver_id" class="border border-gray-200 rounded-xl p-3 mb-3 w-full" placeholder="Driver ID">
    <input id="vi_type" name="type" class="border border-gray-200 rounded-xl p-3 mb-3 w-full" placeholder="Violation Type">
    <input type="date" id="vi_date" name="date" class="border border-gray-200 rounded-xl p-3 mb-3 w-full" placeholder="Date">
    
    <button type="button" onclick="addViolation()" class="bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-2.5 rounded-xl w-full font-semibold">Save Violation</button>

  </div>
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
      <?php while($row = $violations->fetch_assoc()){ ?>
        <tr data-id="<?= $row['id'] ?>">
          <td><?= $row['driver_id'] ?></td>
          <td><?= htmlspecialchars($row['type']) ?></td>
          <td><?= $row['date'] ?></td>
        </tr>
      <?php } ?>
      </tbody>
    </table>
  </div>
</div>
