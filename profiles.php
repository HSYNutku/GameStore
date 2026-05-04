<?php
require_once 'config.php';
checkLogin();

$db = Database::getInstance()->getConnection();
$error = '';
$users = [];

// Arama
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

try {
    if (!empty($search)) {
        $stmt = $db->prepare("
            SELECT id, username, profile_image, bio, join_date 
            FROM users 
            WHERE username LIKE ? OR bio LIKE ?
            ORDER BY username ASC
        ");
        $searchParam = "%{$search}%";
        $stmt->execute([$searchParam, $searchParam]);
    } else {
        $stmt = $db->prepare("
            SELECT id, username, profile_image, bio, join_date 
            FROM users 
            ORDER BY username ASC
        ");
        $stmt->execute();
    }
    $users = $stmt->fetchAll();
} catch(PDOException $e) {
    $error = 'Kullanıcılar yüklenirken hata oluştu.';
}

$page_title = 'Profiller';
$active_page = 'profiles';
include 'includes/header.php';
?>

<div class="container mt-4">

<div class="card">
<div class="card-header">
    <h3 class="mb-0">Kullanıcı Profilleri</h3>
</div>

<div class="card-body">

<!-- ARAMA -->
<form method="GET" class="mb-4">
    <div class="input-group">
        <input type="text" name="search" class="form-control"
            placeholder="Kullanıcı ara..."
            value="<?= htmlspecialchars($search); ?>">
        <button class="btn btn-primary">
            Ara
        </button>
    </div>
</form>

<?php if ($error): ?>
<div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<div class="row row-cols-1 row-cols-md-3 g-4">

<?php foreach ($users as $user): ?>

<?php
// 🔥 ARKADAŞLIK DURUMU
$stmt = $db->prepare("
SELECT * FROM friends 
WHERE (sender_id=? AND receiver_id=?)
   OR (sender_id=? AND receiver_id=?)
");
$stmt->execute([
    $_SESSION['user_id'], $user['id'],
    $user['id'], $_SESSION['user_id']
]);
$friendship = $stmt->fetch();
?>

<div class="col">
<div class="card h-100 text-center">

<img src="<?= !empty($user['profile_image']) ? $user['profile_image'] : 'uploads/profiles/default.jpg'; ?>"
     class="rounded-circle mx-auto mt-3"
     style="width:100px;height:100px;object-fit:cover;">

<div class="card-body">

<h5><?= htmlspecialchars($user['username']) ?></h5>

<?php if (!empty($user['bio'])): ?>
<p class="text-muted small">
    <?= mb_substr(htmlspecialchars($user['bio']), 0, 80) ?>...
</p>
<?php endif; ?>

<p class="small text-muted">
    Katılım: <?= date('d.m.Y', strtotime($user['join_date'])) ?>
</p>

<a href="view_profile.php?id=<?= $user['id'] ?>" class="btn btn-primary btn-sm">
    Profil
</a>

<!-- 🔥 ARKADAŞ BUTONU -->
<?php if ($user['id'] != $_SESSION['user_id']): ?>

    <?php if (!$friendship): ?>
        <a href="add_friend.php?id=<?= $user['id'] ?>" 
           class="btn btn-success btn-sm mt-2">
           ➕ Arkadaş Ekle
        </a>

    <?php elseif ($friendship['status'] == 'pending'): ?>
        <button class="btn btn-warning btn-sm mt-2" disabled>
            ⏳ Bekliyor
        </button>

    <?php else: ?>
        <button class="btn btn-secondary btn-sm mt-2" disabled>
            ✔️ Arkadaş
        </button>
    <?php endif; ?>

<?php endif; ?>

</div>
</div>
</div>

<?php endforeach; ?>

</div>

</div>
</div>

</div>

<?php include 'includes/footer.php'; ?>