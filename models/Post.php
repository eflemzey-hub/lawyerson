<?php
class Post {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll($onlyPublished = false, $searchQuery = '') {
        if (!$this->pdo) return [];
        
        $query = "SELECT * FROM posts ";
        $conditions = [];
        $params = [];

        if ($onlyPublished) {
            $conditions[] = "status = 'published'";
        }

        if (!empty($searchQuery)) {
            $conditions[] = "(title LIKE ? OR content LIKE ?)";
            $params[] = "%" . $searchQuery . "%";
            $params[] = "%" . $searchQuery . "%";
        }

        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }
        
        $query .= " ORDER BY created_at DESC";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        if (!$this->pdo) return null;
        $stmt = $this->pdo->prepare("SELECT * FROM posts WHERE id = ?");
        $stmt->execute([(int) $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPublishedById($id) {
        if (!$this->pdo) return null;
        $stmt = $this->pdo->prepare("SELECT * FROM posts WHERE id = ? AND status = 'published'");
        $stmt->execute([(int) $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getBySlug($slug) {
        if (!$this->pdo) return null;
        $stmt = $this->pdo->prepare("SELECT * FROM posts WHERE slug = ? AND status = 'published'");
        $stmt->execute([$slug]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        if (!$this->pdo) return false;
        $stmt = $this->pdo->prepare("INSERT INTO posts (title, slug, content, category, image_url, status) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['title'] ?? '',
            $data['slug'] ?? '',
            $data['content'] ?? '',
            $data['category'] ?? '',
            $data['image_url'] ?? '',
            $data['status'] ?? 'draft'
        ]);
    }

    public function update($id, $data) {
        if (!$this->pdo) return false;
        $stmt = $this->pdo->prepare("UPDATE posts SET title = ?, slug = ?, content = ?, category = ?, image_url = ?, status = ? WHERE id = ?");
        return $stmt->execute([
            $data['title'] ?? '',
            $data['slug'] ?? '',
            $data['content'] ?? '',
            $data['category'] ?? '',
            $data['image_url'] ?? '',
            $data['status'] ?? 'draft',
            (int) $id
        ]);
    }

    public function delete($id) {
        if (!$this->pdo) return false;
        $stmt = $this->pdo->prepare("DELETE FROM posts WHERE id = ?");
        return $stmt->execute([(int) $id]);
    }
}
