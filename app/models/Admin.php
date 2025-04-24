<?php
namespace models;

use PDO;
class Admin {
    public $adminId;
    public $username;
    private $passwordHash;

    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function login($username, $password) {
        $stmt = $this->pdo->prepare("SELECT * FROM admins WHERE admin_name = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$admin) {
            return false;
        }
        if (password_verify($password, $admin['password'])) {
            session_start();
            $_SESSION['adminLogged'] = true;
            $_SESSION['adminName'] = $admin['admin_name'];
            $_SESSION['adminId'] = $admin['admin_id'];

            return true;
        }
        return false;
    }

    public function getAllAdmins() {
        $stmt = $this->pdo->prepare("SELECT admin_id, admin_name FROM admins ORDER BY admin_name ASC");
        $stmt->execute();
        $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function isLoggedIn() {
        session_start();
        return !empty($_SESSION['adminLogged']);
    }

    public static function logout() {
        session_start();
        unset($_SESSION['adminLogged']);
        session_destroy();
    }

    public function findById($adminId) {
        $stmt = $this->pdo->prepare("SELECT * FROM admins WHERE admin_id = ?");
        $stmt->execute([$adminId]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            $this->$adminId = $data['admin_id'];
            $this->username = $data['admin_name'];
            $this->passwordHash = $data['password'];
            return true;
        }
        return false;
    }

    public function create($username, $password) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO admins (admin_name, password) VALUES (?, ?)");
        return $stmt->execute([$username, $passwordHash]);
    }
    public function update($adminId, $username, $password = null) {
        if ($password) {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->pdo->prepare("UPDATE admins SET admin_name = ?, password = ? WHERE admin_id = ?");
            return $stmt->execute([$username, $passwordHash, $adminId]);
        } else {
            $stmt = $this->pdo->prepare("UPDATE admins SET admin_name = ? WHERE admin_id = ?");
            return $stmt->execute([$username, $adminId]);
        }
    }

    public function delete($adminId) {
        $stmt = $this->pdo->prepare("DELETE FROM admins WHERE admin_id = ?");
        return $stmt->execute([$adminId]);
    }
}

