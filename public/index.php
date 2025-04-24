<?php

use controllers\MessageController;

require_once __DIR__ . '/../app/core/autoload.php';
require_once __DIR__ . '/../app/core/DataBase.php';
try {
    $pdo = DataBase::getInstance();
} catch (Exception $e) {
    echo "Ошибка подключения к базе данных: " . $e->getMessage();
}
$controller = new MessageController($pdo);
$controller->index();
$controller = new MessageController($pdo);
$controller->allMessages();