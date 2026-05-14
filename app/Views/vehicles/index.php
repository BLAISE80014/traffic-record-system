<?php
if (!isset($data)) $data = [];
$vehicles = $data['vehicles'] ?? [];
?>

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
    <form method="POST" action="?">

      <input name="plate" 
        class="border p-3 mb-3 w-full rounded" placeholder="Plate Number">

      <input name="type" 
        class="border p-3 mb-3 w-full rounded" placeholder="Vehicle Type">

      <input name="model" 
        class="border p-3 mb-3 w-full rounded" placeholder="Model">

      <button type="submit" name="saving" 
        class="bg-green-600 text-white w-full p-2 rounded">
        Save Vehicle
      </button>

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
      <?php while($row = $vehicles->fetch_assoc()){ ?>
        <tr class="border-b">
          <td><?= $row['id'] ?></td>
          <td><?= htmlspecialchars($row['plate_number']) ?></td>
          <td><?= htmlspecialchars($row['type']) ?></td>
          <td><?= htmlspecialchars($row['model']) ?></td>

          <td class="flex gap-2">
            <a href="?v_edit=<?= $row['id'] ?>#vehicle" 
              class="bg-blue-500 text-white px-2 py-1 rounded">
              Edit
            </a>

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
