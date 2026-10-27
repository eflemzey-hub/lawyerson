<?php
class Setting {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->ensureTable();
    }

    private function ensureTable() {
        if (!$this->pdo) {
            return;
        }
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS settings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            setting_key VARCHAR(100) NOT NULL UNIQUE,
            setting_value TEXT
        )");
    }

    public function getAll() {
        if ($this->pdo) {
            $stmt = $this->pdo->query("SELECT * FROM settings");
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $settings = [];
            foreach ($results as $row) {
                $settings[$row['setting_key']] = $row['setting_value'];
            }
            return $settings;
        }
        return [];
    }

    public function get($key, $default = '') {
        if ($this->pdo) {
            $stmt = $this->pdo->prepare("SELECT setting_value FROM settings WHERE setting_key = ?");
            $stmt->execute([$key]);
            $result = $stmt->fetchColumn();
            return $result !== false ? $result : $default;
        }
        return $default;
    }

    public function update($key, $value) {
        if ($this->pdo) {
            $stmt = $this->pdo->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
            return $stmt->execute([$key, $value, $value]);
        }
        return false;
    }
}
