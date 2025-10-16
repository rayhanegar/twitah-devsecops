<?php
require_once __DIR__ . '/../models/User.php';

class AuthController {
    private $model;
    private $conn;

    public function __construct($db) {
        $this->model = new User($db);
        $this->conn = $db;
    }

    public function showLogin() {
        include __DIR__ . '/../views/auth/login.php';
    }

    public function showRegister() {
        include __DIR__ . '/../views/auth/register.php';
    }

    // proses login — vulnerable karena model.login menggunakan raw SQL dan password plaintext
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            $user = $this->model->login($email, $password);
            if ($user) {
                // simpan seluruh row user ke session (termasuk password) — intentionally vulnerable
                $_SESSION['user'] = $user;
                header("Location: /index.php");
                exit;
            } else {
                $error = "Email atau password salah!";
                include __DIR__ . '/../views/auth/login.php';
            }
        }
    }

    // proses register — vulnerable karena menyimpan password plaintext via model.register
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            if ($this->model->register($username, $email, $password)) {
                header("Location: /index.php");
                exit;
            } else {
                $error = "Gagal mendaftar!";
                include __DIR__ . '/../views/auth/register.php';
            }
        }
    }

    public function logout() {
    session_unset();
    session_destroy();

    header("Location: /index.php");
    exit;
}

}
?>
