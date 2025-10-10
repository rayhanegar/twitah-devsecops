<?php 
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Tweet.php';

class ProfileController {
    private $userModel;
    private $tweetModel;
    private $db;

    public function __construct($db) {
        $this->db = $db;
        $this->userModel = new User($db);
        $this->tweetModel = new Tweet($db);
    }

    public function show() {
        if (!isset($_SESSION['user'])) {
            header('Location: views/auth/login.php');
            exit;
        }

        if (isset($_GET['id'])) {
            $id = $_GET['id'];

            $result = $this->db->query("SELECT * FROM users WHERE id = '$id' LIMIT 1");
            $profileUser = $result->fetch_assoc();

            if (!$profileUser) {
                echo "<p>User not found.</p>";
                return;
            }

            $tweetsResult = $this->db->query("SELECT * FROM tweets WHERE user_id = " . $profileUser['id'] . " ORDER BY created_at DESC");
            $tweets = [];
            while ($row = $tweetsResult->fetch_assoc()) {
                $tweets[] = $row;
            }
        } 
        else {
            $profileUser = $_SESSION['user'];
            $tweets = $this->tweetModel->getByUserId($profileUser['id']);
        }

        require __DIR__ . '/../views/profile.php';
    }

    public function updateUsername() {
        if (!isset($_SESSION['user'])) {
            header('Location: views/auth/login.php');
            exit;
        }

        $newUsername = $_POST['username'];
        $userId = (int)$_SESSION['user']['id'];

        // Cek duplikasi username
        $check = $this->db->query("SELECT id FROM users WHERE username = '$newUsername' AND id != $userId");
        if ($check->num_rows > 0) {
            echo "<script>alert('Username sudah digunakan!'); window.location='index.php?action=profile';</script>";
            exit;
        }

        // Update username tanpa prepared statement
        $this->db->query("UPDATE users SET username = '$newUsername' WHERE id = $userId");

        // Update session
        $_SESSION['user']['username'] = $newUsername;

        header('Location: index.php?action=profile&profile_updated=1');
        exit;
    }

}

?>
