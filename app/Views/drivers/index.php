<?php
if (!isset($data)) $data = [];
$drivers = $data['drivers'] ?? [];
$search = $data['search'] ?? '';
?>

<div id="driver" class="section p-6">

  <!-- HEADER -->
  <div class="flex justify-between items-center mb-6">
    <div>
      <h2 class="text-2xl font-bold">Drivers</h2>
      <p class="text-gray-500 text-sm">Browse drivers and maintain records.</p>
    </div>
  </div>

  <!-- SEARCH -->
  <form method="GET" class="mb-4 flex gap-2">
    <input type="text" name="search"
      value="<?= htmlspecialchars($search) ?>"
      placeholder="Search driver..."
      class="border p-2 w-full rounded">

    <button class="bg-blue-500 text-white px-4 rounded">Search</button>

    <a href="dashboard.php#driver" class="bg-gray-500 text-white px-4 rounded">Reset</a>
  </form>

  <!-- TABLE -->
  <div class="bg-white p-4 rounded">

    <table id="driverTable" class="w-full border">
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
<?php while($row = $drivers->fetch_assoc()){ ?>
<tr>
  <td><?= htmlspecialchars($row['name']) ?></td>
  <td><?= htmlspecialchars($row['license_number']) ?></td>
  <td><?= htmlspecialchars($row['dob']) ?></td>
  <td><?= htmlspecialchars($row['gender']) ?></td>
  <td><?= htmlspecialchars($row['phone']) ?></td>

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
