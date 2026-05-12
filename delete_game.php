<?php
require_once 'config.php';
checkLogin();
checkAdmin();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$game_id = (int)$_GET['id'];

try {

    $db = Database::getInstance()->getConnection();

    // 🎮 OYUN VAR MI?
    $stmt = $db->prepare("
        SELECT id 
        FROM games 
        WHERE id = ?
    ");

    $stmt->execute([$game_id]);

    if ($stmt->fetch()) {

        // ❌ DELETE YOK
        // ✅ SADECE MAĞAZADAN KALDIR

        $stmt = $db->prepare("
            UPDATE games
            SET status = 'removed'
            WHERE id = ?
        ");

        $stmt->execute([$game_id]);
    }

    header('Location: admin_games.php');
    exit();

} catch(PDOException $e) {

    die('Oyun kaldırılırken bir hata oluştu.');

}
?>
