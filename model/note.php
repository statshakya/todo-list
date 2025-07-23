<?php
class Note {
    private $conn;
    private $table = "tbl_notes";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all notes for a user
    public function getAll($userid) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$userid]);
        return $stmt->fetchAll();
    }

    // Get a single note by ID
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Create a new note
    public function create($title, $content, $userid) {
        $stmt = $this->conn->prepare("
            INSERT INTO {$this->table} (title, content, user_id, created_at, updated_at)
            VALUES (?, ?, ?, NOW(), NOW())
        ");
        return $stmt->execute([$title, $content, $userid]);
    }

    // Update an existing note
    public function update($id, $title, $content) {
        $stmt = $this->conn->prepare("
            UPDATE {$this->table} 
            SET title = ?, content = ?, updated_at = NOW()
            WHERE id = ?
        ");
        return $stmt->execute([$title, $content, $id]);
    }

    // Delete a note
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
