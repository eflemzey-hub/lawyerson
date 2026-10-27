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

    public function getRelatedArticles($currentId, $category, $limit = 3) {
        if (!$this->pdo) return [];
        
        // First, try to get articles from the same category
        $stmt = $this->pdo->prepare("
            SELECT * FROM posts 
            WHERE id != ? 
            AND category = ? 
            AND status = 'published' 
            ORDER BY created_at DESC 
            LIMIT ?
        ");
        $stmt->execute([(int) $currentId, $category, (int) $limit]);
        $relatedPosts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // If we don't have enough articles from the same category, fill with recent posts
        if (count($relatedPosts) < $limit) {
            $remaining = $limit - count($relatedPosts);
            $excludeIds = array_merge([(int) $currentId], array_column($relatedPosts, 'id'));
            $placeholders = implode(',', array_fill(0, count($excludeIds), '?'));
            
            $stmt = $this->pdo->prepare("
                SELECT * FROM posts 
                WHERE id NOT IN ($placeholders) 
                AND status = 'published' 
                ORDER BY created_at DESC 
                LIMIT ?
            ");
            $params = array_merge($excludeIds, [(int) $remaining]);
            $stmt->execute($params);
            $additionalPosts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $relatedPosts = array_merge($relatedPosts, $additionalPosts);
        }
        
        return $relatedPosts;
    }
}
