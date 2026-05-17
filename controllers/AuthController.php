<?php
require_once '../config/database.php';
require_once '../models/User.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        $this->userModel = new User($db);
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $fullname = $_POST['fullname'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];

            if ($password !== $confirm_password) {
                echo "<script>alert('Pendaftaran Gagal: Kata sandi dan konfirmasi tidak cocok!'); window.location.href='../views/register.php';</script>";
                return;
            }

            if (strlen($password) < 8) {
                echo "<script>alert('Pendaftaran Gagal: Kata sandi minimal 8 karakter!'); window.location.href='../views/register.php';</script>";
                return;
            }

            $existingUser = $this->userModel->login($email);
            if ($existingUser) {
                echo "<script>alert('Pendaftaran Gagal: Email sudah terdaftar!'); window.location.href='../views/register.php';</script>";
                return;
            }

            if ($this->userModel->register($fullname, $email, $password)) {
                echo "<script>alert('Pendaftaran Berhasil! Menunggu verifikasi admin.'); window.location.href='../views/login.php';</script>";
            } else {
                echo "<script>alert('Pendaftaran Gagal: Terjadi kesalahan sistem.'); window.location.href='../views/register.php';</script>";
            }
        }
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $user = $this->userModel->login($email);

            if ($user && password_verify($password, $user['password'])) {
                // if ($user['is_verified'] || $user['role'] === 'admin') {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['fullname'] = $user['fullname'];
                    $_SESSION['role'] = $user['role'];

                    if ($user['role'] === 'admin') {
                        header("Location: ../views/dashboard-admin.php");
                    } else {
                        header("Location: ../views/dashboard-user.php");
                    }
                    exit();
            } else {
                echo "<script>alert('LOGIN GAGAL: Email atau Kata Sandi salah!'); window.location.href='../views/login.php';</script>";
            }
        }
    }

    public function approve() {
        checkLogin();
        checkAdmin();
        if (isset($_GET['id'])) {
            if ($this->userModel->approveUser($_GET['id'])) {
                echo "<script>alert('User berhasil diverifikasi!'); window.location.href='../views/dashboard-admin.php';</script>";
            }
        }
    }

    public function logout() {
        session_destroy();
        header("Location: ../views/login.php");
        exit();
    }
}
?>
