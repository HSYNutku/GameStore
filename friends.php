<?php
require_once 'config.php';
checkLogin();

$db = Database::getInstance()->getConnection();

$search = $_GET['search'] ?? '';
$results = [];

// 🔍 Kullanıcı arama
if (!empty($search)) {
    $stmt = $db->prepare("
    SELECT id, username FROM users 
    WHERE username LIKE ? AND id != ?
    ");
    $stmt->execute(["%$search%", $_SESSION['user_id']]);
    $results = $stmt->fetchAll();
}

// 👥 Arkadaşlar (last_active EKLENDİ)
$stmt = $db->prepare("
SELECT f.id as fid, u.id as uid, u.username, u.last_active
FROM friends f
JOIN users u ON 
(u.id = f.sender_id OR u.id = f.receiver_id)
WHERE (f.sender_id=? OR f.receiver_id=?)
AND f.status='accepted'
AND u.id != ?
");
$stmt->execute([$_SESSION['user_id'], $_SESSION['user_id'], $_SESSION['user_id']]);
$friends = $stmt->fetchAll();

// 📩 Gelen istekler
$stmt = $db->prepare("
SELECT f.id, u.username 
FROM friends f
JOIN users u ON f.sender_id = u.id
WHERE f.receiver_id=? AND f.status='pending'
");
$stmt->execute([$_SESSION['user_id']]);
$requests = $stmt->fetchAll();

$page_title = "Arkadaşlar";
include 'includes/header.php';
?>

<div class="container mt-4">

<div class="card mb-4">
<div class="card-header"><h4>👥 Arkadaşlarım</h4></div>
<div class="card-body">

<?php if(empty($friends)): ?>
<p class="text-muted">Henüz arkadaşın yok.</p>
<?php endif; ?>

<?php foreach($friends as $f): ?>

<?php
// 🔥 ONLINE KONTROL
$is_online = false;

if ($f['last_active']) {
    $last = strtotime($f['last_active']);
    $is_online = (time() - $last) < 30; // 30 saniye
}
?>

<div class="d-flex justify-content-between align-items-center mb-2">

    <span>
        <?= htmlspecialchars($f['username']) ?>

        <?php if ($is_online): ?>
            <span class="badge bg-success ms-2">Online</span>
        <?php else: ?>
            <span class="badge bg-secondary ms-2">Offline</span>
        <?php endif; ?>
    </span>

    <div>
        <a href="chat.php?id=<?= $f['uid'] ?>" class="btn btn-primary btn-sm">
            💬 Mesaj
        </a>

        <a href="remove_friend.php?id=<?= $f['fid'] ?>" class="btn btn-danger btn-sm">
            Sil
        </a>
    </div>

</div>

<?php endforeach; ?>

</div>
</div>

<!-- 📩 İSTEKLER -->
<div class="card mb-4">
<div class="card-header"><h4>📩 Gelen İstekler</h4></div>
<div class="card-body">

<?php if(empty($requests)): ?>
<p class="text-muted">İstek yok.</p>
<?php endif; ?>

<?php foreach($requests as $r): ?>
<div class="d-flex justify-content-between align-items-center mb-2">

<span><?= htmlspecialchars($r['username']) ?></span>

<div>
<a href="accept_friend.php?id=<?= $r['id'] ?>" class="btn btn-success btn-sm">
Kabul Et
</a>

<a href="reject_friend.php?id=<?= $r['id'] ?>" class="btn btn-danger btn-sm">
Reddet
</a>
</div>

</div>
<?php endforeach; ?>

</div>
</div>

<!-- 🔍 ARAMA -->
<div class="card">
<div class="card-header"><h4>🔍 Kullanıcı Ara</h4></div>
<div class="card-body">

<form method="GET" class="mb-3">
<input type="text" name="search" class="form-control"
placeholder="Kullanıcı adı..."
value="<?= htmlspecialchars($search) ?>">
</form>

<?php foreach($results as $u): ?>

<?php
$stmt = $db->prepare("
SELECT * FROM friends 
WHERE (sender_id=? AND receiver_id=?)
   OR (sender_id=? AND receiver_id=?)
");
$stmt->execute([
    $_SESSION['user_id'], $u['id'],
    $u['id'], $_SESSION['user_id']
]);
$friendship = $stmt->fetch();
?>

<div class="d-flex justify-content-between mb-2">

<span><?= htmlspecialchars($u['username']) ?></span>

<?php if (!$friendship): ?>
<a href="add_friend.php?id=<?= $u['id'] ?>" class="btn btn-primary btn-sm">
➕ Ekle
</a>

<?php elseif ($friendship['status'] == 'pending'): ?>
<button class="btn btn-warning btn-sm" disabled>
⏳ Bekliyor
</button>

<?php else: ?>
<button class="btn btn-secondary btn-sm" disabled>
✔️ Arkadaş
</button>
<?php endif; ?>

</div>

<?php endforeach; ?>

</div>
</div>

</div>

<?php include 'includes/footer.php'; ?>