<?php
class Attorney {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        if ($this->pdo) {
            $stmt = $this->pdo->query("SELECT * FROM attorneys ORDER BY created_at ASC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }

    public function create($data) {
        if ($this->pdo) {
            $stmt = $this->pdo->prepare("INSERT INTO attorneys (name, position, bio, image_url, facebook, twitter, linkedin) VALUES (?, ?, ?, ?, ?, ?, ?)");
            return $stmt->execute([
                $data['name'], 
                $data['position'], 
                $data['bio'], 
                $data['image_url'],
                $data['facebook'] ?? '',
                $data['twitter'] ?? '',
                $data['linkedin'] ?? ''
            ]);
        }
        return false;
    }

    public function delete($id) {
        if ($this->pdo) {
            $stmt = $this->pdo->prepare("DELETE FROM attorneys WHERE id = ?");
            return $stmt->execute([$id]);
        }
        return false;
    }
}
