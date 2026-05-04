<?php
require_once 'config.php';
checkLogin();

$db = Database::getInstance()->getConnection();

$id = (int)$_GET['id'];

$stmt = $db->prepare("DELETE FROM friends WHERE id=?");
$stmt->execute([$id]);

header("Location: friends.php");