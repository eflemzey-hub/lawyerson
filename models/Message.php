<?php

class Message {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll($limit = null, $offset = 0) {
        if (!$this->pdo) return [];
        $sql = "SELECT * FROM messages ORDER BY created_at DESC";
        if ($limit) {
            $sql .= " LIMIT " . max(1, (int) $limit) . " OFFSET " . max(0, (int) $offset);
        }
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        if (!$this->pdo) return null;
        $stmt = $this->pdo->prepare("SELECT * FROM messages WHERE id = ?");
        $stmt->execute([(int) $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUnreadCount() {
        if (!$this->pdo) return 0;
        $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM messages WHERE status = 'unread'");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] ?? 0;
    }

    public function markAsRead($id) {
        if (!$this->pdo) return false;
        $stmt = $this->pdo->prepare("UPDATE messages SET status = 'read' WHERE id = ?");
        return $stmt->execute([(int) $id]);
    }

    public function markAllAsRead() {
        if (!$this->pdo) return false;
        return $this->pdo->query("UPDATE messages SET status = 'read' WHERE status = 'unread'");
    }

    public function delete($id) {
        if (!$this->pdo) return false;
        $stmt = $this->pdo->prepare("DELETE FROM messages WHERE id = ?");
        return $stmt->execute([(int) $id]);
    }

    public function search($query) {
        if (!$this->pdo) return [];
        $stmt = $this->pdo->prepare("
            SELECT * FROM messages 
            WHERE first_name LIKE ? OR last_name LIKE ? OR email LIKE ? OR subject LIKE ? OR message LIKE ?
            ORDER BY created_at DESC
        ");
        $escapedQuery = str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], $query);
        $searchTerm = "%$escapedQuery%";
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByStatus($status) {
        if (!$this->pdo) return [];
        if (!in_array($status, ['unread', 'read', 'replied'], true)) return [];
        $stmt = $this->pdo->prepare("SELECT * FROM messages WHERE status = ? ORDER BY created_at DESC");
        $stmt->execute([$status]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getReplies($messageId) {
        if (!$this->pdo) return [];
        $stmt = $this->pdo->prepare("SELECT * FROM message_replies WHERE message_id = ? ORDER BY created_at ASC");
        $stmt->execute([(int) $messageId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addReply($messageId, $replyText, $sentVia = 'dashboard') {
        if (!$this->pdo) return false;
        $sentVia = in_array($sentVia, ['dashboard', 'email'], true) ? $sentVia : 'dashboard';
        $stmt = $this->pdo->prepare("
            INSERT INTO message_replies (message_id, reply_text, sent_via, created_at) 
            VALUES (?, ?, ?, NOW())
        ");
        return $stmt->execute([(int) $messageId, $replyText, $sentVia]);
    }

    public function getTotalCount() {
        if (!$this->pdo) return 0;
        $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM messages");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] ?? 0;
    }
}
