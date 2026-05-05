<?php
$stmt = $db->prepare("
SELECT * FROM library 
WHERE user_id=? AND game_id=?
");
$stmt->execute([$_SESSION['user_id'], $game['id']]);
$owned = $stmt->fetch();
?>

<?php if ($owned): ?>
    <button class="btn btn-secondary" disabled>
        ✔️ Kütüphanede
    </button>
<?php else: ?>
    <a href="buy_game.php?id=<?= $game['id'] ?>" class="btn btn-success">
        Satın Al
    </a>
<?php endif; ?>