<?php

namespace models;

use PDO;

class Message {
    private $pdo;

    public $id;
    public $name;
    public $text;
    public $status;
    public $createdAt;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }
    public function create($name, $text) {
        $stmt = $this->pdo->prepare("INSERT INTO messages (name, text, status, createdAt) VALUES (?, ?, 'pending', NOW())");
        $result = $stmt->execute([$name, $text]);
        if ($result) {
            $this->id = $this->pdo->lastInsertId();
            $this->name = $name;
            $this->text = $text;
            $this->status = 'pending';
            $this->createdAt = date('Y-m-d H:i:s');
        }
        return $result;
    }

    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM messages WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            $this->id = $data['id'];
            $this->name = $data['name'];
            $this->text = $data['text'];
            $this->status = $data['status'];
            $this->createdAt = $data['createdAt'];
            return true;
        }
        return false;
    }

    public function getApprovedMessages() {
        $stmt = $this->pdo->prepare("SELECT * FROM messages WHERE status = 'approved' ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    public function getAllMessages() {
        $stmt = $this->pdo->query("SELECT * FROM messages ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function approve() {
        $this->status = 'approved';
        $this->save();
    }

    public function reject() {
        $this->status = 'rejected';
        $this->save();
    }

    public function update($name, $text, $status = 'updated') {
        $name = trim($name);
        $text = trim($text);
        if (empty($name) || empty($text)) {
            throw new InvalidArgumentException("Имя и текст не могут быть пустыми");
        }

        $this->name = $name;
        $this->text = $text;
        $this->status = $status;
        return $this->save();
    }


    private function save() {
        if (!$this->id) {
            throw new Exception("Невозможно обновить сообщение без ID");
        }
        $stmt = $this->pdo->prepare("UPDATE messages SET name = ?, text = ?, status = ? WHERE id = ?");
        return $stmt->execute([$this->name, $this->text, $this->status, $this->id]);
    }

    private function logModerationAction($adminId, $action) {
        $stmt = $this->pdo->prepare("INSERT INTO message_moderation_log (message_id, admin_id, action) VALUES (?, ?, ?)");
        $stmt->execute([$this->id, $adminId, $action]);
    }

    public function getModerationLog() {
        $stmt = $this->pdo->prepare("
        SELECT l.action, l.action_time, a.admin_name
    FROM message_moderation_log l
    JOIN admins a ON l.admin_id = a.admin_id
    WHERE l.message_id = ?
    ORDER BY l.action_time DESC
    ");
        $stmt->execute([$this->id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

