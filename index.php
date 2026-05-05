<?php
// ---------------- DATABASE CONNECTION ----------------
$host = "localhost";
$user = "root";
$pass = "";
$db   = "traffic_system";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ---------------- SIGNUP ----------------
if (isset($_POST['signup'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (name, email, password)
            VALUES ('$name', '$email', '$password')";

    $conn->query($sql);
}

// ---------------- LOGIN ----------------
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            echo "<script>alert('Login successful');</script>";
            header("location: dashboard.php");
            exit();
        } else {
            echo "<script>alert('Wrong password');</script>";
        }
    } else {
        echo "<script>alert('User not found');</script>";
    }
}
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

<form method="POST">
<input name="email" class="mb-4 p-3 border rounded-lg w-full" type="email" placeholder="Email" required />
<input name="password" class="mb-4 p-3 border rounded-lg w-full" type="password" placeholder="Password" required />

<button name="login" class="bg-blue-600 text-white p-3 rounded-lg w-full">
Login
</button>
</form>

</div>

<!-- SIGNUP -->
<div class="w-1/2 p-10 flex flex-col justify-center">

<h2 class="text-3xl font-bold mb-6">Create account</h2>

<form method="POST">
<input name="name" class="mb-4 p-3 border rounded-lg w-full" type="text" placeholder="Name" required />
<input name="email" class="mb-4 p-3 border rounded-lg w-full" type="email" placeholder="Email" required />
<input name="password" class="mb-4 p-3 border rounded-lg w-full" type="password" placeholder="Password" required />

<button name="signup" class="bg-blue-600 text-white p-3 rounded-lg w-full">
Sign Up
</button>
</form>

</div>

</div>

<!-- OVERLAY -->
<div class="absolute top-0 right-0 w-1/2 h-full overlay text-white flex flex-col items-center justify-center p-10">

<h2 class="text-3xl font-bold mb-4" id="overlayTitle">Welcome back!</h2>

<p class="mb-6 text-center">
Login or create account
</p>

<button id="toggleBtn" class="border border-white px-6 py-2 rounded-full">
Sign Up
</button>

</div>

</div>

<!-- JS -->
<script>
 const forms = document.getElementById('forms'); 
 const toggleBtn = document.getElementById('toggleBtn'); 
 const overlayTitle = document.getElementById('overlayTitle'); 
 
 let isLogin = true; 
 toggleBtn.addEventListener('click', () => { 
  forms.classList.toggle('-translate-x-1/2'); 
  
  isLogin = !isLogin; 
  
  if (isLogin) { 
    overlayTitle.innerText = "Welcome back!"; 
    toggleBtn.innerText = "Sign Up"; 
    } else { 
      overlayTitle.innerText = "Hello, Friend!"; 
      toggleBtn.innerText = "Sign In"; 
      } 
      }); 
      </script>
</body>
</html>