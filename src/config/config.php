<?php
$host = getenv('DB_HOST') ?: "sns-dso-db";
$user = getenv('DB_USER') ?: "sns_user";
$pass = getenv('DB_PASSWORD') ?: "";
$dbname = getenv('DB_NAME') ?: "twita_db";

$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
