<?php
require_once '../Core/Controller.php';

class AuthController extends Controller {
    private $userModel;

    public function __construct() {
        $this->userModel = $this->model('User');
    }

    public function signup() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['signup'])) {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if (!empty($name) && !empty($email) && !empty($password)) {
                $this->userModel->create($name, $email, $password);
                header("Location: /TRS/public/index.php?success=Signup successful");
                exit();
            }
        }
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $user = $this->userModel->findByEmail($email);

            if ($user) {
                if ($this->userModel->verifyPassword($password, $user['password'])) {
                    session_start();
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['user_email'] = $user['email'];
                    header("Location: dashboard.php");
                    exit();
                } else {
                    header("Location: /TRS/public/index.php?error=Wrong password");
                    exit();
                }
            } else {
                header("Location: /TRS/public/index.php?error=User not found");
                exit();
            }
        }
    }

    public function logout() {
        session_start();
        session_destroy();
        header("Location: /TRS/public/index.php");
        exit();
    }
}
?>
