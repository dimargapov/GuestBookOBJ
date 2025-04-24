<?php
namespace controllers;

use models\Admin;

use Exception;
use InvalidArgumentException;
class adminController {
    private $adminModel;
    public function __construct() {
        $this->adminModel = new Admin($pdo);
    }
    public function loginForm() {
        require 'app/views/admin/login.php';
    }
    public function login() {
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');
        if ($this->adminModel->login($username, $password)) {
            header('Location: /admin/messages');
            exit;
        } else {
            echo 'Неверное имя пользователя или пароль!';
            require 'app/views/admin/login.php';
        }
    }
    public function logout() {
        session_start();
        $_SESSION = [];
        session_destroy();
        header('Location: /admin/login');
        exit;
    }

    public function list() {
        session_start();
        if (empty($_SESSION['adminLogged'])) {
            header('Location: /admin/login');
            exit;
        }
        $admins = $this->adminModel->getAllAdmins();
        require 'app/views/admin/admins.php';
    }
    public function createForm() {
        require 'app/views/admin/adminCreate.php';
    }
    public function create() {
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');
        try {
            $this->adminModel->create($username, $password);
            header('Location: /admin/admins');
            exit;
        } catch (Exception $e) {
            echo $e->getMessage();
            require 'app/views/admin/adminCreate.php';
        }
    }

    public function editForm($adminId) {
        if ($this->adminModel->findById($adminId)) {
            $admin = $this->adminModel;
            require 'app/views/admin/adminEdit.php';
        } else {
            echo 'Админ не найден';
        }
    }

    public function updateAdmin($adminId) {
        if (!$this->adminModel->findById($adminId)) {
            echo 'Админ не найден';
            return;
        }
        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');
        try {
            $this->adminModel->update($adminId, $username, $password);
            header('Location: /admin/admins');
            exit;
        } catch (InvalidArgumentException $e) {
            $error = $e->getMessage();
            $message = $this->adminModel;
            require 'app/views/admin/adminEdit.php';
        }
    }

    public function deleteAdmin($adminId) {
        if (!$this->adminModel->findById($adminId)) {
            echo 'Админ не найден!';
            return;
        }
        $this->adminModel->delete($adminId);
        header('Location: /admin/admins');
        exit;
    }
}