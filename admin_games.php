<?php
require_once 'config.php';
checkLogin();
checkAdmin();

$db = Database::getInstance()->getConnection();

// ONAYLA
if (isset($_GET['approve'])) {
    $id = (int)$_GET['approve'];
    $db->prepare("UPDATE games SET status='approved' WHERE id=?")->execute([$id]);
}

// REDDET
if (isset($_GET['reject'])) {
    $id = (int)$_GET['reject'];
    $db->prepare("UPDATE games SET status='rejected' WHERE id=?")->execute([$id]);
}

$page_title = "Admin Onay Paneli";
$active_page = "admin";
include 'includes/header.php';

$games = $db->query("SELECT * FROM games WHERE status='pending'")->fetchAll();
?>

<div class="container mt-4">
    <h2>🛠️ Onay Bekleyen Oyunlar</h2>

    <div class="row">
        <?php foreach ($games as $game): ?>
        <div class="col-md-4">
            <div class="card mb-4 shadow">

                <?php if($game['image_url']): ?>
                    <img src="<?= $game['image_url'] ?>" class="card-img-top">
                <?php endif; ?>

                <div class="card-body">
                    <h5><?= htmlspecialchars($game['name']) ?></h5>
                    <p><?= htmlspecialchars($game['genre']) ?></p>
                    <p><b>$<?= $game['price'] ?></b></p>

                    <a href="?approve=<?= $game['id'] ?>" class="btn btn-success btn-sm">
                        ✔️ Onayla
                    </a>

                    <a href="?reject=<?= $game['id'] ?>" class="btn btn-danger btn-sm">
                        ❌ Reddet
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>