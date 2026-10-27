<?php
class Attorney {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll($onlyActive = false) {
        if ($this->pdo) {
            $query = "SELECT * FROM attorneys";
            if ($onlyActive) {
                $query .= " WHERE status = 'active'";
            }
            $query .= " ORDER BY display_order ASC, created_at ASC";
            $stmt = $this->pdo->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }

    public function getById($id) {
        if ($this->pdo) {
            $stmt = $this->pdo->prepare("SELECT * FROM attorneys WHERE id = ?");
            $stmt->execute([(int) $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return null;
    }

    public function create($data) {
        if ($this->pdo) {
            $stmt = $this->pdo->prepare("
                INSERT INTO attorneys (
                    name, position, bio, image_url, 
                    email, phone, specialty, years_experience,
                    education, bar_admissions, achievements, notable_cases,
                    facebook, twitter, linkedin, status, display_order
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            return $stmt->execute([
                $data['name'] ?? '', 
                $data['position'] ?? '', 
                $data['bio'] ?? '', 
                $data['image_url'] ?? '',
                $data['email'] ?? '',
                $data['phone'] ?? '',
                $data['specialty'] ?? '',
                $data['years_experience'] ?? 0,
                $data['education'] ?? '',
                $data['bar_admissions'] ?? '',
                $data['achievements'] ?? '',
                $data['notable_cases'] ?? '',
                $data['facebook'] ?? '',
                $data['twitter'] ?? '',
                $data['linkedin'] ?? '',
                $data['status'] ?? 'active',
                $data['display_order'] ?? 0
            ]);
        }
        return false;
    }

    public function update($id, $data) {
        if ($this->pdo) {
            $stmt = $this->pdo->prepare("
                UPDATE attorneys SET
                    name = ?, position = ?, bio = ?, image_url = ?,
                    email = ?, phone = ?, specialty = ?, years_experience = ?,
                    education = ?, bar_admissions = ?, achievements = ?, notable_cases = ?,
                    facebook = ?, twitter = ?, linkedin = ?, status = ?, display_order = ?
                WHERE id = ?
            ");
            return $stmt->execute([
                $data['name'] ?? '',
                $data['position'] ?? '',
                $data['bio'] ?? '',
                $data['image_url'] ?? '',
                $data['email'] ?? '',
                $data['phone'] ?? '',
                $data['specialty'] ?? '',
                $data['years_experience'] ?? 0,
                $data['education'] ?? '',
                $data['bar_admissions'] ?? '',
                $data['achievements'] ?? '',
                $data['notable_cases'] ?? '',
                $data['facebook'] ?? '',
                $data['twitter'] ?? '',
                $data['linkedin'] ?? '',
                $data['status'] ?? 'active',
                $data['display_order'] ?? 0,
                (int) $id
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
