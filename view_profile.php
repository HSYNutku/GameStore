<?php
require_once 'config.php';
checkLogin();

$db = Database::getInstance()->getConnection();
$user = null;
$error = '';

// URL'den kullanıcı ID'sini al
$profile_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

try {
    // Kullanıcı bilgilerini getir
    $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$profile_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        $error = 'Kullanıcı bulunamadı.';
    }

    // 🔥 ARKADAŞLIK DURUMU
    $stmt = $db->prepare("
    SELECT * FROM friends 
    WHERE (sender_id=? AND receiver_id=?) 
       OR (sender_id=? AND receiver_id=?)
    ");
    $stmt->execute([
        $_SESSION['user_id'], $profile_id,
        $profile_id, $_SESSION['user_id']
    ]);
    $friendship = $stmt->fetch();

    // Forum gönderileri
    $stmt = $db->prepare("
        SELECT fp.*, COUNT(pl.id) as like_count, COUNT(fc.id) as comment_count
        FROM forum_posts fp
        LEFT JOIN post_likes pl ON fp.id = pl.post_id
        LEFT JOIN forum_comments fc ON fp.id = fc.post_id
        WHERE fp.user_id = ?
        GROUP BY fp.id
        ORDER BY fp.created_at DESC
        LIMIT 5
    ");
    $stmt->execute([$profile_id]);
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Yorumlar
    $stmt = $db->prepare("
        SELECT c.*, g.name as game_name
        FROM comments c
        JOIN games g ON c.game_id = g.id
        WHERE c.user_id = ?
        ORDER BY c.created_at DESC
        LIMIT 5
    ");
    $stmt->execute([$profile_id]);
    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    $error = 'Profil bilgileri yüklenirken hata oluştu.';
}

$page_title = $user ? htmlspecialchars($user['username']) : 'Profil';
include 'includes/header.php';
?>

<div class="container mt-4">

<?php if ($error): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php elseif ($user): ?>

<div class="row">

    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">

                <img src="<?= !empty($user['profile_image']) ? $user['profile_image'] : 'uploads/profiles/default.jpg'; ?>" 
                     class="rounded-circle mb-3"
                     style="width:150px;height:150px;object-fit:cover;">

                <h3><?= htmlspecialchars($user['username']) ?></h3>
                <p class="text-muted">
                    Üyelik: <?= date('d.m.Y', strtotime($user['join_date'])) ?>
                </p>

                <!-- 🔥 ARKADAŞ BUTONU -->
                <?php if ($profile_id != $_SESSION['user_id']): ?>

                    <?php if (!$friendship): ?>
                        <a href="add_friend.php?id=<?= $profile_id ?>" 
                           class="btn btn-success w-100 mt-2">
                           ➕ Arkadaş Ekle
                        </a>

                    <?php elseif ($friendship['status'] == 'pending'): ?>
                        <button class="btn btn-warning w-100 mt-2" disabled>
                            ⏳ İstek Gönderildi
                        </button>

                    <?php else: ?>
                        <button class="btn btn-secondary w-100 mt-2" disabled>
                            ✔️ Arkadaşsınız
                        </button>
                    <?php endif; ?>

                <?php endif; ?>

                <?php if ($user['bio']): ?>
                    <hr>
                    <h5>Hakkında</h5>
                    <p><?= nl2br(htmlspecialchars($user['bio'])) ?></p>
                <?php endif; ?>

                <?php if ($user['favorite_game']): ?>
                    <h5>Favori Oyun</h5>
                    <p><?= htmlspecialchars($user['favorite_game']) ?></p>
                <?php endif; ?>

                <?php if ($user['steam_profile']): ?>
                    <a href="<?= $user['steam_profile'] ?>" class="btn btn-primary mt-2" target="_blank">
                        Steam Profili
                    </a>
                <?php endif; ?>

            </div>
        </div>
    </div>

    <div class="col-md-8">

        <!-- Forum -->
        <div class="card mb-4">
            <div class="card-header">
                <h4>Son Forum Gönderileri</h4>
            </div>
            <div class="card-body">

                <?php if (empty($posts)): ?>
                    <p class="text-muted">Gönderi yok.</p>
                <?php else: ?>
                    <?php foreach ($posts as $post): ?>
                        <div class="mb-3">
                            <h5>
                                <a href="community.php?post=<?= $post['id'] ?>">
                                    <?= htmlspecialchars($post['title']) ?>
                                </a>
                            </h5>
                            <small>
                                <?= $post['like_count'] ?> beğeni - 
                                <?= $post['comment_count'] ?> yorum
                            </small>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

            </div>
        </div>

        <!-- Yorumlar -->
        <div class="card">
            <div class="card-header">
                <h4>Son Yorumlar</h4>
            </div>
            <div class="card-body">

                <?php if (empty($comments)): ?>
                    <p class="text-muted">Yorum yok.</p>
                <?php else: ?>
                    <?php foreach ($comments as $c): ?>
                        <div class="mb-3">
                            <h5>
                                <a href="game_details.php?id=<?= $c['game_id'] ?>">
                                    <?= htmlspecialchars($c['game_name']) ?>
                                </a>
                            </h5>
                            <p><?= htmlspecialchars($c['comment']) ?></p>
                            ⭐ <?= $c['rating'] ?>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

            </div>
        </div>

    </div>

</div>

<?php endif; ?>

</div>

<?php include 'includes/footer.php'; ?>