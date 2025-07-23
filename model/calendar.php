<?php
class Calendar {
    private $conn;
    private $table = "tbl_events";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all events for a user
    public function getAll($userid) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE user_id = ? ORDER BY event_date ASC");
        $stmt->execute([$userid]);
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($title, $description, $event_date, $userid) {
        $stmt = $this->conn->prepare("
            INSERT INTO {$this->table} (title, description, event_date, user_id, created_at, updated_at)
            VALUES (?, ?, ?, ?, NOW(), NOW())
        ");
        return $stmt->execute([$title, $description, $event_date, $userid]);
    }

    public function addEvent($user_id, $title, $description, $event_date) {
    $query = "INSERT INTO {$this->table} (user_id, title, description, event_date) VALUES (?, ?, ?, ?)";
    $stmt = $this->conn->prepare($query);
    return $stmt->execute([$user_id, $title, $description, $event_date]);
}


    public function update($id, $title, $description, $event_date) {
        $stmt = $this->conn->prepare("
            UPDATE {$this->table}
            SET title = ?, description = ?, event_date = ?, updated_at = NOW()
            WHERE id = ?
        ");
        return $stmt->execute([$title, $description, $event_date, $id]);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
