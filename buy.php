<?php
session_start();
include("config/db.php");

if (!isset($_SESSION['user_id'])) {
    die("Giriş yap!");
}

$user_id = $_SESSION['user_id'];
$game_id = $_GET['id'];

$game = $conn->query("SELECT * FROM games WHERE id=$game_id")->fetch_assoc();
$user = $conn->query("SELECT * FROM users WHERE id=$user_id")->fetch_assoc();

if ($user['balance'] >= $game['price']) {

    $conn->query("UPDATE users SET balance = balance - {$game['price']} WHERE id=$user_id");
    $conn->query("INSERT INTO purchases (user_id, game_id) VALUES ($user_id, $game_id)");

    echo "<h2>Satın alındı!</h2><a href='index.php'>Geri dön</a>";

} else {
    echo "<h2>Yetersiz bakiye!</h2>";
}