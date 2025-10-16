<?php
// controllers/SubscriptionController.php
class SubscriptionController {
    private $db;

    public function __construct($db) {
        $this->db = $db; // mysqli connection
    }

    // Upgrade: set role = 'ningrat'
    // Accept POST['user_id'] OR if not provided upgrade current session user
    // INTENTIONALLY VULNERABLE: tidak ada pemeriksaan hak, menerima user_id dari input
    public function subscribe() {
        // prioritas ambil dari POST, lalu GET, lalu session user id
        $userId = $_POST['user_id'] ?? $_GET['user_id'] ?? ($_SESSION['user']['id'] ?? null);

        if (!$userId) {
            header('Location: /index.php?action=subscription');
            exit;
        }

        // Tanpa pemeriksaan: langsung ubah role
        $sql = "UPDATE users SET role = 'ningrat' WHERE id = " . (int)$userId;
        $this->db->query($sql);
        
        $_SESSION['user']['role'] = 'ningrat';

        // redirect kembali ke halaman upgrade dengan flag
        header('Location: /index.php?action=subscription&subscribed=1');
        exit;
    }

    // Downgrade: set role = 'jelata'
    public function unsubscribe() {

        $userId = $_POST['user_id'] ?? $_GET['user_id'] ?? ($_SESSION['user']['id'] ?? null);

        if (!$userId) {
            header('Location: /index.php?action=subs');
            exit;
        }

        // Tanpa pemeriksaan
        $sql = "UPDATE users SET role = 'jelata' WHERE id = " . (int)$userId;
        $this->db->query($sql);

        $_SESSION['user']['role'] = 'jelata';

        header('Location: /index.php?action=subscription&unsubscribed=1');
        exit;
    }

    // helper: get username by id (dipakai bila mau redirect ke profile)
    public function getUsernameById($id) {
        $res = $this->db->query("SELECT username FROM users WHERE id = " . (int)$id . " LIMIT 1");
        if ($res && $r = $res->fetch_assoc()) return $r['username'];
        return '';
    }
}
?>
