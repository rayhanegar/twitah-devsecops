<?php
class User {
    private $conn;
    private $table = "users";

    public function __construct($db) {
        $this->conn = $db;
    }

    // REGISTER: simpan password plaintext (vulnerable)
    public function register($username, $email, $password) {
        $sql = "INSERT INTO {$this->table} (username, email, password) VALUES ('" .
               $this->escape($username) . "', '" . $this->escape($email) . "', '" . $this->escape($password) . "')";
        return $this->conn->query($sql);
    }

    // LOGIN: raw SQL (vulnerable to SQLi)
    public function login($email, $password) {
        $sql = "SELECT * FROM {$this->table} WHERE email = '" . $this->escape($email) . "' AND password = '" . $this->escape($password) . "' LIMIT 1";
        $res = $this->conn->query($sql);
        if ($res && $res->num_rows === 1) {
            return $res->fetch_assoc();
        }
        return false;
    }

    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = " . (int)$id . " LIMIT 1";
        $res = $this->conn->query($sql);
        if ($res && $res->num_rows === 1) {
            return $res->fetch_assoc();
        }
        return null;
    }

    private function escape($val) {
        // minimal escaping using mysqli_real_escape_string — still unsafe if bypassed in demo
        return $this->conn->real_escape_string($val);
    }
}
