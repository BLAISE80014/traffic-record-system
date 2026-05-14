<?php
if (!isset($data)) $data = [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Login / Signup</title>
<script src="https://cdn.tailwindcss.com"></script>

<style>
.overlay {
  background: linear-gradient(135deg, #1e3a8a, #1d4ed8, #2563eb);
}
</style>
</head>

<body class="flex items-center justify-center min-h-screen bg-gray-100">

<div class="w-[900px] h-[550px] bg-white rounded-2xl shadow-2xl flex overflow-hidden relative">

<!-- FORMS -->
<div class="w-full flex transition-transform duration-700" id="forms">

<!-- LOGIN -->
<div class="w-1/2 p-10 flex flex-col justify-center">

<h2 class="text-3xl font-bold mb-6">Sign in</h2>

<?php if (isset($_GET['error'])): ?>
<div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded-lg">
    <?php echo htmlspecialchars($_GET['error']); ?>
</div>
<?php endif; ?>

<form method="POST" action="/TRS/app/Controllers/AuthController.php">
<input name="email" class="mb-4 p-3 border rounded-lg w-full" type="email" placeholder="Email" required />
<input name="password" class="mb-4 p-3 border rounded-lg w-full" type="password" placeholder="Password" required />

<button name="login" class="bg-blue-600 text-white p-3 rounded-lg w-full mb-4">
Login
</button>
</form>

<div class="text-center mb-4">
<span class="text-gray-500">or</span>
</div>

<a href="#" class="text-blue-600 underline text-center" onclick="toggleForm()">
Create an account
</a>

</div>

<!-- SIGNUP -->
<div class="w-1/2 p-10 flex flex-col justify-center hidden" id="signupForm">

<h2 class="text-3xl font-bold mb-6">Create Account</h2>

<form method="POST" action="/TRS/app/Controllers/AuthController.php">
<input name="name" class="mb-4 p-3 border rounded-lg w-full" type="text" placeholder="Full Name" required />
<input name="email" class="mb-4 p-3 border rounded-lg w-full" type="email" placeholder="Email" required />
<input name="password" class="mb-4 p-3 border rounded-lg w-full" type="password" placeholder="Password" required />

<button name="signup" class="bg-green-600 text-white p-3 rounded-lg w-full mb-4">
Sign Up
</button>
</form>

<div class="text-center mb-4">
<span class="text-gray-500">or</span>
</div>

<a href="#" class="text-blue-600 underline text-center" onclick="toggleForm()">
Already have an account?
</a>

</div>

</div>

<!-- OVERLAY -->
<div class="absolute w-1/2 h-full overlay right-0 rounded-r-2xl flex items-center justify-center text-white p-10 hidden" id="overlay">
<div class="text-center">
<h3 class="text-4xl font-bold mb-4">Welcome!</h3>
<p class="mb-6">Join our traffic management system today</p>
</div>
</div>

</div>

<script>
function toggleForm() {
  document.getElementById('forms').classList.toggle('-translate-x-full');
  document.getElementById('overlay').classList.toggle('hidden');
  document.getElementById('signupForm').classList.toggle('hidden');
}
</script>

</body>
</html>
