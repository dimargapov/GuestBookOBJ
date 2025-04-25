<?php

namespace controllers;

use models\Message;

use models\Admin;

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
        ob_start();
        require(__DIR__ . '/../views/admin/messages.php');
        $content = ob_get_clean();
        return $content;
    }

    public function createForm() {
        require 'app/views/message/create.php';
    }

    public function create() {
        $name = trim($_POST['name'] ?? '');
        $text = trim($_POST['text'] ?? '');
        if (empty($name) || empty($text)) {
            $error = 'Поля должны быть заполнены!';
            return;
        }
        if ($this->messageModel->create($name, $text)) {
            $successMessage = 'Сообщение отправлено на модерацию!';
            header('Location: /projectObj/public/index.php?action=list');
            exit;
        } else {
            $error = 'Ошибка при сохранении сообщения';
        }
    }

    public function approveMessage($id) {
            $this->messageModel->findById($id);
            $this->messageModel->approve();
            header('Location: /projectObj/public/index.php?action=messages');
            exit;
    }
    public function rejectMessage($id) {
        if ($this->messageModel->findById($id)) {
            $this->messageModel->reject();
            header('Location: /projectObj/public/index.php?action=messages');
            exit;
        } else {
            $error = 'Сообщение не найдено';
        }
    }
    public function editForm($id) {
        $message = $this->messageModel->findById($id);
        require (__DIR__ . '/../views/message/edit_form.php');
        exit;
    }
    public function updateMessage($id) {
        if (!$this->messageModel->findById($id)) {
            echo 'Сообщение не найдено';
            return;
        }
        $name = trim($_POST['name'] ?? '');
        $text = trim($_POST['text'] ?? '');
        try {
            $this->messageModel->update($name, $text, 'updated');
            header('Location: /projectObj/public/index.php?action=edit&id='.$id);
            exit;
        } catch (InvalidArgumentException $e) {
            $error = $e->getMessage();
            $message = $this->messageModel;
            require 'app/views/message/edit.php';
        }
    }
}
