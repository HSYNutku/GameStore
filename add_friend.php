<?php
require_once 'config.php';
checkLogin();

$db = Database::getInstance()->getConnection();

$receiver_id = (int)$_GET['id'];
$sender_id = $_SESSION['user_id'];

// kendine istek gönderemesin
if ($receiver_id == $sender_id) {
    header("Location: profiles.php");
    exit();
}

// zaten varsa tekrar eklemesin
$stmt = $db->prepare("
SELECT * FROM friends 
WHERE (sender_id=? AND receiver_id=?) 
   OR (sender_id=? AND receiver_id=?)
");
$stmt->execute([$sender_id,$receiver_id,$receiver_id,$sender_id]);

if ($stmt->rowCount() == 0) {
    $stmt = $db->prepare("
    INSERT INTO friends (sender_id, receiver_id) 
    VALUES (?, ?)
    ");
    $stmt->execute([$sender_id, $receiver_id]);
}

header("Location: profiles.php");