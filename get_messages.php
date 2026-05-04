<?php
require_once 'config.php';
checkLogin();

$db = Database::getInstance()->getConnection();

$user_id = $_SESSION['user_id'];
$other = (int)$_GET['user_id'];

// 🔥 ÖNCE OKUNDU YAP
$db->prepare("
UPDATE messages 
SET is_read = 1 
WHERE sender_id = ? AND receiver_id = ?
")->execute([$other, $user_id]);

// 🔥 SONRA MESAJLARI ÇEK
$stmt = $db->prepare("
SELECT * FROM messages 
WHERE (sender_id=? AND receiver_id=?)
   OR (sender_id=? AND receiver_id=?)
ORDER BY created_at ASC
");

$stmt->execute([$user_id,$other,$other,$user_id]);
$messages = $stmt->fetchAll();

foreach($messages as $m){

    if($m['sender_id'] == $user_id){
        echo "<div style='text-align:right;color:lime;'>".$m['message']."</div>";
    } else {
        echo "<div style='text-align:left;color:white;'>".$m['message']."</div>";
    }

}