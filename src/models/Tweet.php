<?php
class Tweet {
    private $conn;
    private $table = "tweets";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Ambil semua tweet (raw query ok)
    public function getAllTweets() {
        $query = "SELECT t.id, t.content, t.image_url, t.created_at, u.username 
                  FROM tweets t 
                  JOIN users u ON t.user_id = u.id 
                  ORDER BY t.created_at DESC";
        return $this->conn->query($query);
    }

    // tambah tweet — vulnerable karena menggunakan concatenation dan tidak memvalidasi content
    public function addTweet($user_id, $content, $image_url = null) {
        $u = $this->conn->real_escape_string((string)$user_id);
        $c = $this->conn->real_escape_string((string)$content);
        $i = $image_url ? $this->conn->real_escape_string((string)$image_url) : '';
        $sql = "INSERT INTO {$this->table} (user_id, content, image_url) VALUES ($u, '$c', '$i')";
        return $this->conn->query($sql);
    }

    public function searchTweets($keyword) {
    // VULNERABLE: raw query concatenation (SQL Injection possible)
    $sql = "SELECT tweets.id, tweets.content, tweets.image_url, tweets.created_at, users.username FROM tweets JOIN users ON tweets.user_id = users.id WHERE tweets.content LIKE '%$keyword%' OR users.username LIKE '%$keyword%' ORDER BY tweets.created_at DESC";
    return $this->conn->query($sql);
    }

    public function getTweetById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = $id";
        $result = $this->conn->query($sql);
        return $result ? $result->fetch_assoc() : null;
    }

    public function updateTweet($id, $content, $image_url) {
        // VULNERABLE: tanpa escaping — raw SQL
        $sql = "UPDATE {$this->table} 
                SET content = '$content', image_url = '$image_url'
                WHERE id = $id";
        return $this->conn->query($sql);
    }
    public function getByUserId($user_id) {
        $u = (int)$user_id; // cast to int for safety
        $sql = "SELECT * FROM {$this->table} WHERE user_id = $u ORDER BY created_at DESC";
        $res = $this->conn->query($sql);
        if ($res) {
            return $res->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    }
}
?>
