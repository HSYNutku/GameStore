<?php
require_once 'config.php';
checkLogin();

$db = Database::getInstance()->getConnection();

$sender = $_SESSION['user_id'];
$receiver = (int)$_POST['receiver_id'];
$message = trim($_POST['message']);

if (!empty($message)) {
    $stmt = $db->prepare("
    INSERT INTO messages (sender_id, receiver_id, message)
    VALUES (?, ?, ?)
    ");
    $stmt->execute([$sender, $receiver, $message]);
}