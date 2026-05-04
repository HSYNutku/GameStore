<?php
require_once 'config.php';
checkLogin();

$db = Database::getInstance()->getConnection();

$game_id = (int)$_GET['id'];
$user_id = $_SESSION['user_id'];

$stmt = $db->prepare("INSERT INTO cart (user_id, game_id) VALUES (?, ?)");
$stmt->execute([$user_id, $game_id]);

header("Location: cart.php");
exit();
$stmt = $db->prepare("SELECT * FROM cart WHERE user_id=? AND game_id=?");
$stmt->execute([$user_id, $game_id]);

if($stmt->rowCount() == 0){
    $stmt = $db->prepare("INSERT INTO cart (user_id, game_id) VALUES (?, ?)");
    $stmt->execute([$user_id, $game_id]);
}