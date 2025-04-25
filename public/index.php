<?php

use controllers\MessageController;
use controllers\AdminController;
use models\Admin;
use models\Message;

require_once __DIR__ . '/../app/core/autoload.php';
require_once __DIR__ . '/../app/core/DataBase.php';
try {
    $pdo = DataBase::getInstance();
} catch (Exception $e) {
    echo "Ошибка подключения к базе данных: " . $e->getMessage();
}
$controller = new MessageController($pdo);

//$controller->index();
//echo $controller->allMessages();
$action = $_GET['action'] ?? 'index';
$id = $_GET['id'] ?? null;

switch($action) {
    case 'messages':
        echo $controller->allMessages();
        break;
    case 'contact':
        echo $controller->contactForm();
        break;
    case 'list':
        echo $controller->index();
        break;
    case 'create':
        echo $controller->create();
        break;
    case 'approve':
        echo $controller->approveMessage($id);
        break;
    case 'reject':
        echo $controller->rejectMessage($id);
        break;
    case 'edit':
        echo $controller->editForm($id);
        break;
    case 'update':
        echo $controller->updateMessage($id);
        break;

}

