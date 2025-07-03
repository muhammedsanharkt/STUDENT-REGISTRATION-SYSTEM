<?php

require_once __DIR__ . '/../database/db.php';




class Student {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }

    public function register($name, $email, $course, $number) {
        $sql = "INSERT INTO students (name, email, course, number) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        try {
            return $stmt->execute([$name, $email, $course, $number]);
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                // Duplicate entry
                return false;
            } else {
                throw $e; // Other errors
            }
        }
    }

    public function getAll() {
        $sql = "SELECT * FROM students";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete($id) {
        $sql = "DELETE FROM students WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$id]);
    }
    public function addAdmin($username, $password) {
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO admins (username, password) VALUES (?, ?)";
    $stmt = $this->conn->prepare($sql);
    try {
        return $stmt->execute([$username, $hashed]);
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            return false; // Duplicate username
        } else {
            throw $e;
        }
    }
}

}
?>
