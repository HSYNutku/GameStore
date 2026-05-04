<?php
require_once 'config.php';
checkLogin();

$db = Database::getInstance()->getConnection();
$error = '';
$game = null;

// ID kontrolü
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$game_id = (int)$_GET['id'];

try {

    // 🔥 SADECE ONAYLI VEYA KENDİ EKLEDİĞİ OYUN
    $stmt = $db->prepare("
        SELECT g.*, u.username as publisher_name 
        FROM games g 
        LEFT JOIN users u ON g.added_by = u.id 
        WHERE g.id = ? 
        AND (g.status = 'approved' OR g.added_by = ?)
    ");
    
    $stmt->execute([$game_id, $_SESSION['user_id']]);
    $game = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$game) {
        header('Location: index.php');
        exit();
    }

} catch(PDOException $e) {
    $error = 'Oyun bilgileri yüklenirken hata oluştu.';
}

// Yorum ekleme
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comment'])) {
    $comment = trim($_POST['comment']);
    $rating = (int)$_POST['rating'];

    if (empty($comment) || $rating < 1 || $rating > 5) {
        $error = 'Geçerli yorum girin.';
    } else {
        try {
            $stmt = $db->prepare("
                INSERT INTO comments (game_id, user_id, comment, rating) 
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([$game_id, $_SESSION['user_id'], $comment, $rating]);
        } catch(PDOException $e) {
            $error = 'Yorum hatası';
        }
    }
}

// Yorumlar
$stmt = $db->prepare("
    SELECT c.*, u.username 
    FROM comments c 
    JOIN users u ON c.user_id = u.id 
    WHERE c.game_id = ? 
    ORDER BY c.created_at DESC
");
$stmt->execute([$game_id]);
$comments = $stmt->fetchAll();

$page_title = $game['name'];
include 'includes/header.php';
?>

<div class="container mt-4">

<?php if ($error): ?>
<div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<div class="row">

    <div class="col-md-8">
        <?php if ($game['image_url']): ?>
        <img src="<?= $game['image_url'] ?>" class="img-fluid mb-3">
        <?php endif; ?>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-body">

                <h2><?= htmlspecialchars($game['name']) ?></h2>

                <p><b>Tür:</b> <?= $game['genre'] ?></p>
                <p><b>Yayıncı:</b> <?= $game['publisher'] ?></p>

                <p><b>Ekleyen:</b> 
                    <a href="view_profile.php?id=<?= $game['added_by'] ?>">
                        <?= htmlspecialchars($game['publisher_name']) ?>
                    </a>
                </p>

                <h3 class="text-success">$<?= $game['price'] ?></h3>

                <a href="add_to_cart.php?id=<?= $game['id'] ?>" class="btn btn-success w-100 mt-3">
                🛒 Sepete Ekle
                </a>

                <?php if ($game['status'] == 'pending'): ?>
                    <div class="alert alert-warning">
                        ⏳ Bu oyun admin onayı bekliyor
                    </div>
                <?php endif; ?>

                <?php if ($_SESSION['is_admin'] == 1): ?>
                <a href="edit_game.php?id=<?= $game['id'] ?>" class="btn btn-primary w-100 mb-2">
                    Düzenle
                </a>
                <a href="delete_game.php?id=<?= $game['id'] ?>" class="btn btn-danger w-100">
                    Sil
                </a>
                <a href="add_to_cart.php?id=<?= $game['id'] ?>" class="btn btn-success">
    Sepete Ekle
</a>
                <?php endif; ?>

            </div>
        </div>
    </div>

</div>

<div class="card mt-4">
    <div class="card-body">
        <h4>Açıklama</h4>
        <p><?= nl2br(htmlspecialchars($game['description'])) ?></p>
    </div>
</div>

<!-- Yorum -->
<div class="card mt-4">
    <div class="card-body">

        <h4>Yorum Yap</h4>

        <form method="POST">
            <select name="rating" class="form-select mb-2" required>
                <option value="">Puan seç</option>
                <option value="5">5</option>
                <option value="4">4</option>
                <option value="3">3</option>
                <option value="2">2</option>
                <option value="1">1</option>
            </select>

            <textarea name="comment" class="form-control mb-2" required></textarea>

            <button class="btn btn-success">Gönder</button>
        </form>

    </div>
</div>

<!-- Yorumlar -->
<div class="card mt-4">
    <div class="card-body">
        <h4>Yorumlar</h4>

        <?php foreach ($comments as $c): ?>
        <div class="mb-3">
            <b><?= htmlspecialchars($c['username']) ?></b>
            ⭐ <?= $c['rating'] ?>
            <p><?= htmlspecialchars($c['comment']) ?></p>
            <hr>
        </div>
        <?php endforeach; ?>

    </div>
</div>

</div>

<?php include 'includes/footer.php'; ?>