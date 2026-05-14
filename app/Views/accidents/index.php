<?php
if (!isset($data)) $data = [];
$accidents = $data['accidents'] ?? [];
?>

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
      <tbody>
      <?php while($row = $accidents->fetch_assoc()){ ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= $row['driver_id'] ?></td>
          <td><?= $row['vehicle_id'] ?></td>
          <td><?= htmlspecialchars($row['location']) ?></td>
          <td><?= $row['date'] ?></td>
          <td><?= htmlspecialchars($row['description']) ?></td>
        </tr>
      <?php } ?>
      </tbody>
    </table>
  </div>
</div>
