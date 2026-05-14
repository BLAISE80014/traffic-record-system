<?php
session_start();

// ================= DATABASE CONNECTION =================
$host = "localhost";
$user = "root";
$pass = "";
$db   = "traffic_system";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

// ================= GOOGLE CONFIG =================
include 'google_config.php';

// ================= SIGNUP =================
if (isset($_POST['signup'])) {

    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // CHECK IF EMAIL EXISTS
    $check = $conn->prepare("SELECT id FROM users WHERE email=?");
    $check->bind_param("s", $email);
    $check->execute();
    $checkResult = $check->get_result();

    if ($checkResult->num_rows > 0) {

        echo "<script>alert('Email already exists');</script>";

    } else {

        // INSERT USER
        $stmt = $conn->prepare("
            INSERT INTO users(name,email,password)
            VALUES(?,?,?)
        ");

        $stmt->bind_param("sss", $name, $email, $password);

        if ($stmt->execute()) {

            // ================= SEND EMAIL =================
            $to = $email;
            $subject = "Welcome To Traffic Record System";

            $message = "
            <html>
            <head>
                <title>Welcome</title>
            </head>

            <body style='font-family:Arial;padding:20px;'>

                <div style='max-width:600px;margin:auto;background:#f4f4f4;padding:30px;border-radius:10px;'>

                    <h2 style='color:#2563eb;'>
                        Welcome $name
                    </h2>

                    <p>
                        Your account has been created successfully.
                    </p>

                    <p>
                        Thank you for joining Traffic Record System.
                    </p>

                    <br>

                    <a href='http://localhost/traffic/dashboard.php'
                       style='background:#2563eb;
                              color:white;
                              padding:12px 20px;
                              text-decoration:none;
                              border-radius:5px;'>
                        Open Dashboard
                    </a>

                </div>

            </body>
            </html>
            ";

            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= "From: Traffic Record System <yourgmail@gmail.com>" . "\r\n";

            mail($to, $subject, $message, $headers);

            echo "<script>alert('Signup Successful');</script>";

        } else {

            echo "<script>alert('Signup Failed');</script>";

        }
    }
}

// ================= LOGIN =================
if (isset($_POST['login'])) {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // SAFE QUERY
    $stmt = $conn->prepare("
        SELECT * FROM users WHERE email=?
    ");

    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        $user = $result->fetch_assoc();

        // VERIFY PASSWORD
        if (password_verify($password, $user['password'])) {

            // SESSION
            $_SESSION['user_id']    = $user['id'];
            $_SESSION['user_name']  = $user['name'];
            $_SESSION['user_email'] = $user['email'];

            // ================= LOGIN EMAIL =================
            $to = $user['email'];
            $subject = "Login Alert";

            $message = "
            <html>
            <body style='font-family:Arial;padding:20px;'>

                <div style='max-width:600px;margin:auto;background:#f4f4f4;padding:30px;border-radius:10px;'>

                    <h2 style='color:#2563eb;'>
                        Hello ".$user['name']."
                    </h2>

                    <p>
                        You logged into your account successfully.
                    </p>

                    <p>
                        If this was not you, change your password immediately.
                    </p>

                </div>

            </body>
            </html>
            ";

            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= "From: Traffic Record System <yourgmail@gmail.com>" . "\r\n";

            mail($to, $subject, $message, $headers);

            // REDIRECT
            header("location: dashboard.php");
            exit();

        } else {

            echo "<script>alert('Wrong Password');</script>";

        }

    } else {

        echo "<script>alert('User Not Found');</script>";

    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Traffic Record System</title>

<script src="https://cdn.tailwindcss.com"></script>

<style>

.overlay{
    background: linear-gradient(135deg,#1e3a8a,#2563eb);
}

</style>

</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">

<div class="w-[900px] h-[550px] bg-white rounded-2xl shadow-2xl overflow-hidden relative flex">

    <!-- FORMS -->
    <div class="w-full flex transition-transform duration-700" id="forms">

        <!-- LOGIN -->
        <div class="w-1/2 p-10 flex flex-col justify-center">

            <h2 class="text-3xl font-bold mb-6">
                Sign In
            </h2>

            <form method="POST">

                <input
                    type="email"
                    name="email"
                    placeholder="Email"
                    required
                    class="w-full p-3 border rounded-lg mb-4"
                >

                <input
                    type="password"
                    name="password"
                    placeholder="Password"
                    required
                    class="w-full p-3 border rounded-lg mb-4"
                >

                <button
                    name="login"
                    class="w-full bg-blue-600 text-white p-3 rounded-lg"
                >
                    Login
                </button>

            </form>

            <div class="text-center my-4 text-gray-500">
                OR
            </div>

            <a
                href="<?php echo $google_client->createAuthUrl(); ?>"
                class="bg-red-600 text-white p-3 rounded-lg text-center block"
            >
                Sign In With Google
            </a>

        </div>

        <!-- SIGNUP -->
        <div class="w-1/2 p-10 flex flex-col justify-center">

            <h2 class="text-3xl font-bold mb-6">
                Create Account
            </h2>

            <form method="POST">

                <input
                    type="text"
                    name="name"
                    placeholder="Full Name"
                    required
                    class="w-full p-3 border rounded-lg mb-4"
                >

                <input
                    type="email"
                    name="email"
                    placeholder="Email"
                    required
                    class="w-full p-3 border rounded-lg mb-4"
                >

                <input
                    type="password"
                    name="password"
                    placeholder="Password"
                    required
                    class="w-full p-3 border rounded-lg mb-4"
                >

                <button
                    name="signup"
                    class="w-full bg-blue-600 text-white p-3 rounded-lg"
                >
                    Sign Up
                </button>

            </form>

            <div class="text-center my-4 text-gray-500">
                OR
            </div>

            <a
                href="<?php echo $google_client->createAuthUrl(); ?>"
                class="bg-red-600 text-white p-3 rounded-lg text-center block"
            >
                Sign Up With Google
            </a>

        </div>

    </div>

    <!-- OVERLAY -->
    <div class="absolute top-0 right-0 w-1/2 h-full overlay text-white flex flex-col items-center justify-center p-10">

        <h2 class="text-3xl font-bold mb-4" id="overlayTitle">
            Welcome Back!
        </h2>

        <p class="mb-6 text-center">
            Login or create account
        </p>

        <button
            id="toggleBtn"
            class="border border-white px-6 py-2 rounded-full"
        >
            Sign Up
        </button>

    </div>

</div>

<!-- JAVASCRIPT -->
<script>

const forms = document.getElementById('forms');
const toggleBtn = document.getElementById('toggleBtn');
const overlayTitle = document.getElementById('overlayTitle');

let isLogin = true;

toggleBtn.addEventListener('click', () => {

    forms.classList.toggle('-translate-x-1/2');

    isLogin = !isLogin;

    if(isLogin){

        overlayTitle.innerText = "Welcome Back!";
        toggleBtn.innerText = "Sign Up";

    }else{

        overlayTitle.innerText = "Hello Friend!";
        toggleBtn.innerText = "Sign In";

    }

});

</script>

</body>
</html>