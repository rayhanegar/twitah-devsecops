<?php
session_start();

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/TweetController.php';

$action = $_GET['action'] ?? '';

$auth = new AuthController($conn);
$tweet = new TweetController($conn);

switch ($action) {
    case 'login':
        $auth->login();
        break;
    case 'register':
        $auth->register();
        break;
    case 'logout':
        $auth->logout();
        break;
    case 'loginForm':
        $auth->showLogin();
        break;
    case 'registerForm':
        $auth->showRegister();
        break;
    case 'showAdd':
        $tweet->showAdd();
        break;
    case 'storeTweet':
        $tweet->store();
        break;
    default:
        $tweet->index();
        break;
}
?>
