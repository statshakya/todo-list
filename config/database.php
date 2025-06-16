<?php
class Database {
    private $host = "localhost";
    private $db_name = "todolist";
    private $username = "root";
    private $password = "1234";
    private $conn;

    public function connect() {
        if ($this->conn) return $this->conn;

        try {
            $dsn = "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
            ];
            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
            // echo "✅ Database connected successfully.<br>"; // ← Add this line
        } catch (PDOException $e) {
            die("❌ Connection failed: " . $e->getMessage());
        }

        return $this->conn;
    }
}
