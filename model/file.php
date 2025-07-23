<?php
class File {
    private $conn;
    private $table = "tbl_files";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Save file info to database
    public function create($filename, $filepath, $filesize, $filetype, $userid) {
        $stmt = $this->conn->prepare("
            INSERT INTO {$this->table} (filename, filepath, filesize, filetype, user_id, uploaded_at)
            VALUES (?, ?, ?, ?, ?, NOW())
        ");
        return $stmt->execute([$filename, $filepath, $filesize, $filetype, $userid]);
    }

    // Get all files for a user
    public function getAll($userid) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE user_id = ? ORDER BY uploaded_at DESC");
        $stmt->execute([$userid]);
        return $stmt->fetchAll();
    }

    // Get file by ID
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Delete file record by ID
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
