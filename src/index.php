<?php
session_start();

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/TweetController.php';
require_once __DIR__ . '/controllers/ProfileController.php';
require_once __DIR__ . '/controllers/SubscriptionController.php';

$action = $_GET['action'] ?? '';

$auth = new AuthController($conn);
$tweet = new TweetController($conn);
$profile = new ProfileController($conn);
$subs = new SubscriptionController($conn);

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
    case 'updateTweet':
        $tweet->updateTweet();
        break;
    case 'deleteTweet':
        $tweet->deleteTweet();
        break;
    case 'profile':
        $profile->show();
        break;
    case 'updateUsername':
        $profile->updateUsername();
        break;
    case 'subscription':
        include __DIR__ . '/views/subscription.php';
        break;
    case 'subs':
        $subs->subscribe();
        break;
    case 'unsubs':
        $subs->unsubscribe();
    default:
        $tweet->index();
        break;
}
?>
