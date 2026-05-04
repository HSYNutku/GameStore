<?php
require_once 'config.php';
checkLogin();

$db = Database::getInstance()->getConnection();

// Kullanıcının satın aldığı oyunlar
$stmt = $db->prepare("
SELECT DISTINCT g.* 
FROM order_items oi
JOIN orders o ON oi.order_id = o.id
JOIN games g ON oi.game_id = g.id
WHERE o.user_id = ?
");
$stmt->execute([$_SESSION['user_id']]);
$games = $stmt->fetchAll();

$page_title = "Kütüphanem";
include 'includes/header.php';
?>

<div class="container mt-4">

    <h2 class="mb-4">🎮 Kütüphanem</h2>

    <?php if (empty($games)): ?>
        <div class="alert alert-warning">
            Henüz satın aldığınız oyun yok 😢
        </div>
    <?php else: ?>

        <div class="row row-cols-1 row-cols-md-3 g-4">

            <?php foreach ($games as $game): ?>
            <div class="col">
                <div class="card h-100 shadow">

                    <?php if ($game['image_url']): ?>
                        <img src="<?= $game['image_url'] ?>" class="card-img-top">
                    <?php endif; ?>

                    <div class="card-body">
                        <h5><?= htmlspecialchars($game['name']) ?></h5>

                        <p class="text-success">✔️ Satın alındı</p>

                        <a href="game_details.php?id=<?= $game['id'] ?>" class="btn btn-primary btn-sm">
                            Detay
                        </a>

                        <button class="btn btn-success btn-sm mt-2">
                            ▶️ Oyna
                        </button>
                    </div>

                </div>
            </div>
            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</div>

<?php include 'includes/footer.php'; ?>