<?php
class Testimonial {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll($onlyActive = true) {
        if (!$this->pdo) return [];
        
        $query = "SELECT * FROM testimonials";
        
        if ($onlyActive) {
            $query .= " WHERE status = 'active'";
        }
        
        $query .= " ORDER BY display_order ASC, created_at DESC";

        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        if (!$this->pdo) return null;
        $stmt = $this->pdo->prepare("SELECT * FROM testimonials WHERE id = ?");
        $stmt->execute([(int) $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByPracticeArea($practiceArea, $limit = null) {
        if (!$this->pdo) return [];
        
        $query = "SELECT * FROM testimonials WHERE practice_area = ? AND status = 'active' ORDER BY display_order ASC";
        
        if ($limit) {
            $query .= " LIMIT " . (int) $limit;
        }
        
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([$practiceArea]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        if (!$this->pdo) return false;
        $stmt = $this->pdo->prepare("
            INSERT INTO testimonials (client_name, client_title, client_company, practice_area, rating, review_text, status, display_order) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['client_name'] ?? '',
            $data['client_title'] ?? '',
            $data['client_company'] ?? '',
            $data['practice_area'] ?? '',
            $data['rating'] ?? 5,
            $data['review_text'] ?? '',
            $data['status'] ?? 'active',
            $data['display_order'] ?? 0
        ]);
    }

    public function update($id, $data) {
        if (!$this->pdo) return false;
        $stmt = $this->pdo->prepare("
            UPDATE testimonials 
            SET client_name = ?, client_title = ?, client_company = ?, practice_area = ?, rating = ?, review_text = ?, status = ?, display_order = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['client_name'] ?? '',
            $data['client_title'] ?? '',
            $data['client_company'] ?? '',
            $data['practice_area'] ?? '',
            $data['rating'] ?? 5,
            $data['review_text'] ?? '',
            $data['status'] ?? 'active',
            $data['display_order'] ?? 0,
            (int) $id
        ]);
    }

    public function delete($id) {
        if (!$this->pdo) return false;
        $stmt = $this->pdo->prepare("DELETE FROM testimonials WHERE id = ?");
        return $stmt->execute([(int) $id]);
    }
}
