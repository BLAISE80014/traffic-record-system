<?php
if (!isset($data)) $data = [];
$payments = $data['payments'] ?? [];
?>

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
      <tbody>
      <?php while($row = $payments->fetch_assoc()){ ?>
        <tr>
          <td><?= $row['driver_id'] ?></td>
          <td><?= number_format($row['amount'], 2) ?></td>
        </tr>
      <?php } ?>
      </tbody>
    </table>
  </div>
</div>
