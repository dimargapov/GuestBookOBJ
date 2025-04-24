<?php

namespace controllers;

use models\Message;

class MessageController {
    private Message $messageModel;

    public function __construct($pdo) {
        $this->messageModel = new Message($pdo);
    }

    public function index() {
        $messages = $this->messageModel->getApprovedMessages();
        require __DIR__ . '/../views/message/list.php';
    }

    public function allMessages() {
//        session_start();
//        if (empty($_SESSION['adminLogged'])) {
//            header('Location: /admin/login');
//            exit;
//        }
        $messages = $this->messageModel->getAllMessages();
        require 'app/views/admin/messages.php';
    }

    public function createForm() {
        require 'app/views/message/create.php';
    }

    public function create() {
        $name = trim($_POST['name'] ?? '');
        $text = trim($_POST['text'] ?? '');
        if (empty($name) || empty($text)) {
            $error = 'Поля должны быть заполнены!';
            require 'app/views/message/create.php';
            return;
        }
        if ($this->messageModel->create($name, $text)) {
            header('Location: /messages');
            exit;
        } else {
            $error = 'Ошибка при сохранении сообщения';
            require 'app/views/message/create.php';
        }
    }

    public function approve($id, $adminId) {
        if ($this->messageModel->findById($id)) {
            $this->messageModel->approve($adminId);
            header('Location: /admin/messages');
            exit;
        } else {
            $error = 'Сообщение не найдено';
        }
    }
    public function reject($id, $adminId) {
        if ($this->messageModel->findById($id)) {
            $this->messageModel->reject($adminId);
            header('Location: /admin/messages');
            exit;
        } else {
            $error = 'Сообщение не найдено';
        }
    }
    public function editForm($id) {
        if ($this->messageModel->findById($id)) {
            $message = $this->messageModel;
            require 'app/views/message/edit.php';
        } else {
            $error = 'Сообщение не найдено';
        }
    }
    public function update($id) {
        if (!$this->messageModel->findById($id)) {
            echo 'Сообщение не найдено';
            return;
        }
        $name = trim($_POST['name'] ?? '');
        $text = trim($_POST['text'] ?? '');
        try {
            $this->messageModel->update($name, $text, 'updated');
            header('Location: /admin/messages');
            exit;
        } catch (InvalidArgumentException $e) {
            $error = $e->getMessage();
            $message = $this->messageModel;
            require 'app/views/message/edit.php';
        }
    }
}
