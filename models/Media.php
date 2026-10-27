<?php
/**
 * Media Upload Manager
 * 
 * Handles secure file uploads with strict validation.
 * SVG uploads are DISABLED by default for security.
 */

class Media {
    private $pdo;
    private $uploadDir = 'uploads/';

    public function __construct($pdo) {
        $this->pdo = $pdo;
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }

    /**
     * Upload and validate an image file
     * 
     * Security features:
     * - Strict MIME type validation
     * - File extension whitelist
     * - Double extension attack prevention
     * - Size limit enforcement (5MB)
     * - Random filename generation
     * - SVG disabled by default (XSS risk)
     * 
     * @param array $file File from $_FILES array
     * @return string File path on success
     * @throws Exception On validation failure
     */
    public function upload($file) {
        // Strict whitelist - SVG DISABLED for security
        // To enable SVG: add 'svg' => 'image/svg+xml' and implement sanitization
        $allowedTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'webp' => 'image/webp',
            'gif' => 'image/gif',
        ];
        $maxSize = 5 * 1024 * 1024; // 5MB

        // Validate upload request
        if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK || !is_uploaded_file($file['tmp_name'])) {
            throw new Exception("Security Error: Invalid upload request.");
        }

        // Extract and validate file extension
        $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        // Detect actual MIME type (not trusting client-provided type)
        $detectedType = '';
        if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $detectedType = $finfo ? finfo_file($finfo, $file['tmp_name']) : '';
            if ($finfo) {
                finfo_close($finfo);
            }
        }
        if ($detectedType === '' && function_exists('mime_content_type')) {
            $detectedType = mime_content_type($file['tmp_name']);
        }

        // Strict validation: extension must match detected MIME type
        if (!isset($allowedTypes[$fileExt]) || $detectedType !== $allowedTypes[$fileExt]) {
            throw new Exception("Security Error: Invalid file type. Only JPG, PNG, WEBP, and GIF are allowed.");
        }

        // Validate file size
        if ((int) $file['size'] <= 0 || (int) $file['size'] > $maxSize) {
            throw new Exception("File is too large. Max size is 5MB.");
        }

        // Additional validation for image files
        if (!$this->validateImage($file['tmp_name'], $fileExt)) {
            throw new Exception("File is not a valid image or contains suspicious content.");
        }

        // Generate cryptographically secure random filename
        $randomName = bin2hex(random_bytes(16));
        $fileName = $randomName . '.' . ($fileExt === 'jpeg' ? 'jpg' : $fileExt);
        $filePath = $this->uploadDir . $fileName;

        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            // Set secure file permissions (read-only for group/others)
            @chmod($filePath, 0644);
            
            // Store in database
            if ($this->pdo) {
                $stmt = $this->pdo->prepare("INSERT INTO media (file_name, file_path, file_type, file_size) VALUES (?, ?, ?, ?)");
                $stmt->execute([$fileName, $filePath, $detectedType, (int) $file['size']]);
            }
            
            return $filePath;
        }
        
        throw new Exception("Failed to upload file.");
    }

    /**
     * Validate that the file is actually a valid image
     * 
     * @param string $tmpPath Temporary file path
     * @param string $extension File extension
     * @return bool True if valid image
     */
    private function validateImage($tmpPath, $extension) {
        // Skip getimagesize for SVG (if ever enabled)
        if ($extension === 'svg') {
            return $this->validateSvg($tmpPath);
        }

        // Use getimagesize to validate image structure
        $imageInfo = @getimagesize($tmpPath);
        if ($imageInfo === false) {
            return false;
        }

        // Verify dimensions are reasonable (not 0x0 or excessively large)
        if ($imageInfo[0] <= 0 || $imageInfo[1] <= 0 || $imageInfo[0] > 10000 || $imageInfo[1] > 10000) {
            return false;
        }

        return true;
    }

    /**
     * Validate and sanitize SVG files (if SVG upload is enabled)
     * 
     * WARNING: SVG files can contain JavaScript and should be sanitized
     * or disabled entirely for maximum security.
     * 
     * @param string $tmpPath Temporary file path
     * @return bool True if valid and safe SVG
     */
    private function validateSvg($tmpPath) {
        $content = file_get_contents($tmpPath);
        
        // Basic validation: check for SVG tag
        if (stripos($content, '<svg') === false) {
            return false;
        }

        // Check for dangerous content
        $dangerousPatterns = [
            '/<script/i',
            '/javascript:/i',
            '/on\w+\s*=/i', // Event handlers like onclick, onload, etc.
            '/<iframe/i',
            '/<embed/i',
            '/<object/i',
        ];

        foreach ($dangerousPatterns as $pattern) {
            if (preg_match($pattern, $content)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get media file by ID
     * 
     * @param int $id Media ID
     * @return array|null Media record or null
     */
    public function getById($id) {
        if (!$this->pdo) {
            return null;
        }
        $stmt = $this->pdo->prepare("SELECT * FROM media WHERE id = ?");
        $stmt->execute([(int) $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Get all media files ordered by upload date
     * 
     * @return array Array of media records
     */
    public function getAll() {
        if ($this->pdo) {
            $stmt = $this->pdo->query("SELECT * FROM media ORDER BY uploaded_at DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }

    /**
     * Delete a media file and its database record
     * 
     * @param int $id Media ID
     * @return bool Success status
     */
    public function delete($id) {
        if ($this->pdo) {
            $stmt = $this->pdo->prepare("SELECT file_path FROM media WHERE id = ?");
            $stmt->execute([(int) $id]);
            $media = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($media) {
                // Secure file deletion with path traversal protection
                $path = str_replace('\\', '/', $media['file_path']);
                $uploadRoot = realpath($this->uploadDir);
                $target = realpath($path);
                
                // Ensure file is within uploads directory (prevent path traversal)
                if ($uploadRoot && $target && strpos($target, $uploadRoot . DIRECTORY_SEPARATOR) === 0 && is_file($target)) {
                    unlink($target);
                }
                
                // Remove database record
                $stmt = $this->pdo->prepare("DELETE FROM media WHERE id = ?");
                return $stmt->execute([(int) $id]);
            }
        }
        return false;
    }
}
