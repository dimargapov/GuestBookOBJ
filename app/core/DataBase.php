<?php
class DataBase {
    private static $instance = null;
    private $pdo;
    private function __construct() {
        $host = "localhost";
        $dbname = "so_sta1";
        $user = "sta1";
        $password = "dt0fIDWv9X";

        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
        try {
            $this->pdo = new PDO($dsn, $user, $password);
        } catch (PDOException $e) {
            echo "Ошибка подключения: " . $e->getMessage();
        }
    }
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->pdo;
    }
}