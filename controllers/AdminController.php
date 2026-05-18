<?php
require_once '../config/database.php';
require_once '../models/Admin.php';

class AdminController {
    private $adminModel;

    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        $this->adminModel = new Admin($db);
    }

    public function handleAction() {
        if (isset($_GET['action'])) {
            if ($_GET['action'] == 'approve' && isset($_GET['id'])) {
                if ($this->adminModel->approveUser($_GET['id'])) {
                    echo "<script>alert('User berhasil di-approve!'); window.location.href='../views/admin/users.php';</script>";
                }
            }
        }
    }
}

$adminCtrl = new AdminController();
$adminCtrl->handleAction();
?>