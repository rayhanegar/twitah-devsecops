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
    $sql = "SELECT t.id, t.content, t.image_url, t.created_at, u.username 
            FROM tweets t 
            JOIN users u ON t.user_id = u.id 
            WHERE t.content LIKE '%$keyword%' 
               OR u.username LIKE '%$keyword%'
            ORDER BY t.created_at DESC";
    return $this->conn->query($sql);
}

}
?>
