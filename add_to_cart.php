<?php
require_once 'config.php';
checkLogin();

$db = Database::getInstance()->getConnection();

$game_id = (int)$_GET['id'];
$user_id = $_SESSION['user_id'];

// 🔥 ÖNCE KONTROL
$stmt = $db->prepare("SELECT id FROM cart WHERE user_id=? AND game_id=?");
$stmt->execute([$user_id, $game_id]);

if ($stmt->fetch()) {
    die("❌ Bu oyun zaten sepette!");
}

// 🔥 SONRA EKLE
$stmt = $db->prepare("INSERT INTO cart (user_id, game_id) VALUES (?, ?)");
$stmt->execute([$user_id, $game_id]);

header("Location: cart.php");
exit;
