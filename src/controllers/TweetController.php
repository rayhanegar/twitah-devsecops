<?php
require_once __DIR__ . '/../models/Tweet.php';

class TweetController {
    private $model;
    private $db;
    private $uploadDir;

    public function __construct($db) {
        $this->db = $db;
        $this->model = new Tweet($db);
        $this->uploadDir = __DIR__ . '/../uploads/';
    }

    public function index() {
        // Cek apakah ada parameter pencarian
        $query = $_GET['q'] ?? '';
        if ($query !== '') {
            $tweets = $this->model->searchTweets($query);
        } else {
            $tweets = $this->model->getAllTweets();
        }

        include __DIR__ . '/../views/home.php';
    }

    public function showAdd() {
        if(!isset($_SESSION['user'])) {
            header("Location: /index.php?action=loginForm");
            exit;
        }
        include __DIR__ . '/../views/add.php';
    }

    public function showEdit() {
        if(!isset($_SESSION['user'])) {
            header("Location: views/auth/login.php");
            exit;
        }
        $id = $_GET['id'] ?? null;

        $tweet = $this->model->getTweetById($id);
        if (!$tweet) {
            echo "Gagal mengambil tweet.";
            return;
        }

        include __DIR__ . '/../views/edit.php';
    }

    public function store() {
        // Ambil user_id dari session jika ada; jika tidak, set ke 1 (demo)
        $user_id = $_SESSION['user']['id'] ?? 1;
        $content = $_POST['content'] ?? '';

        // UPLOAD: **TIDAK** ada validasi tipe/ekstensi — intentionally vulnerable
        $image_url = null;
        if (!empty($_FILES['image']['name'])) {
            $file = $_FILES['image'];
            $newName = uniqid('img_', true) . '_' . basename($file['name']);
            $dest = __DIR__ . '/../uploads/' . $newName;
            move_uploaded_file($file['tmp_name'], $dest);
            $image_url = 'uploads/' . $newName;
        }

        $ok = $this->model->addTweet($user_id, $content, $image_url);
        if ($ok) {
            header("Location: /index.php");
            exit;
        } else {
            $error = "Gagal menyimpan tweet.";
            include __DIR__ . '/../views/add.php';
        }
    }
    public function updateTweet() {
        $id = $_POST['id'] ?? null;
        $content = $_POST['content'] ?? '';
        $image_url = $_POST['image_url'] ?? null; // default: pakai image lama

        // jika user upload gambar baru, ganti
        if (!empty($_FILES['image']['name'])) {
            $file = $_FILES['image'];
            $newName = uniqid('img_', true) . '_' . basename($file['name']);
            $dest = __DIR__ . '/../uploads/' . $newName;
            move_uploaded_file($file['tmp_name'], $dest);
            $image_url = 'uploads/' . $newName;
        }

        $res = $this->model->updateTweet($id, $content, $image_url);
        if ($res) {
            $_SESSION['flash'] = 'Edit tweet berhasil.';
            header("Location: index.php?action=profile");
        } else {
            $error = "Gagal memperbarui tweet.";
            include __DIR__ . '/../views/edit.php';
        }
    }
    public function deleteTweet() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = $_POST['id'];

        // Hapus tweet dari database
        $query = "DELETE FROM tweets WHERE id = '$id'";
        $this->db->query($query);

        // Ambil username user aktif agar bisa redirect ke halaman profil yang sama
        $username = $_SESSION['user']['username'] ?? null;

        if ($username) {
            header("Location: /index.php?action=profile&username={$username}&deleted=1");
        } else {
            header("Location: /index.php?action=profile&deleted=1");
        }
        exit;
    }
}

}
?>
