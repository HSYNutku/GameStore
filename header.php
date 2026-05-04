<?php
$request_count = 0;
$notifs = [];
$unread_count = 0;

if (isset($_SESSION['user_id'])) {
    try {
        $db = Database::getInstance()->getConnection();

        // 🟢 ONLINE AKTİF GÜNCELLE
        $db->prepare("
            UPDATE users 
            SET last_active = NOW() 
            WHERE id = ?
        ")->execute([$_SESSION['user_id']]);

        // 👥 ARKADAŞ İSTEK SAYISI
        $stmt = $db->prepare("
            SELECT COUNT(*) 
            FROM friends 
            WHERE receiver_id = ? AND status = 'pending'
        ");
        $stmt->execute([$_SESSION['user_id']]);
        $request_count = (int)$stmt->fetchColumn();

        // 🔔 SON İSTEKLER
        $stmt = $db->prepare("
            SELECT u.username 
            FROM friends f
            JOIN users u ON f.sender_id = u.id
            WHERE f.receiver_id = ? AND f.status = 'pending'
            ORDER BY f.id DESC
            LIMIT 5
        ");
        $stmt->execute([$_SESSION['user_id']]);
        $notifs = $stmt->fetchAll();

        // 💬 OKUNMAMIŞ MESAJ SAYISI
        $stmt = $db->prepare("
            SELECT COUNT(*) 
            FROM messages 
            WHERE receiver_id = ? AND is_read = 0
        ");
        $stmt->execute([$_SESSION['user_id']]);
        $unread_count = (int)$stmt->fetchColumn();

    } catch (Exception $e) {
        $request_count = 0;
        $notifs = [];
        $unread_count = 0;
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?= isset($page_title) ? $page_title . ' - ' : '' ?>GameStore</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">

<style>
.dropdown-menu {
    background-color: #212529;
    border: 1px solid #373b3e;
}
.dropdown-item {
    color: #fff;
}
.dropdown-item:hover {
    background-color: #373b3e;
}
</style>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
<div class="container">

<a class="navbar-brand" href="index.php">GameStore</a>

<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
<span class="navbar-toggler-icon"></span>
</button>

<div class="collapse navbar-collapse" id="navbarNav">

<!-- SOL -->
<ul class="navbar-nav me-auto">

<li class="nav-item">
<a class="nav-link <?= ($active_page ?? '')=='home'?'active':'' ?>" href="index.php">
Ana Sayfa
</a>
</li>

<li class="nav-item">
<a class="nav-link" href="library.php">
🎮 Kütüphanem
</a>
</li>

<?php if (isset($_SESSION['user_id'])): ?>

<li class="nav-item">
<a class="nav-link <?= ($active_page ?? '')=='add_game'?'active':'' ?>" href="add_game.php">
➕ Oyun Ekle
</a>
</li>

<li class="nav-item">
<a class="nav-link" href="friends.php">
👥 Arkadaşlar 
<?php if ($request_count > 0): ?>
<span class="badge bg-danger"><?= $request_count ?></span>
<?php endif; ?>
</a>
</li>

<li class="nav-item">
<a class="nav-link" href="friends.php">
💬 Mesajlar
<?php if ($unread_count > 0): ?>
<span class="badge bg-danger"><?= $unread_count ?></span>
<?php endif; ?>
</a>
</li>

<?php endif; ?>

<?php if (!empty($_SESSION['is_admin'])): ?>
<li class="nav-item">
<a class="nav-link" href="admin_games.php">
🛠️ Admin
</a>
</li>
<?php endif; ?>

<li class="nav-item dropdown">
<a class="nav-link dropdown-toggle <?= ($active_page ?? '')=='community'?'active':'' ?>" href="#" data-bs-toggle="dropdown">
👥 Topluluk
</a>
<ul class="dropdown-menu">
<li><a class="dropdown-item" href="community.php">Forum</a></li>
<li><a class="dropdown-item" href="profiles.php">Profiller</a></li>
</ul>
</li>

</ul>

<!-- SAĞ -->
<ul class="navbar-nav align-items-center">

<?php if (isset($_SESSION['user_id'])): ?>

<li class="nav-item dropdown me-2">
<a class="nav-link position-relative" href="#" data-bs-toggle="dropdown">
🔔
<?php if ($request_count > 0): ?>
<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
<?= $request_count ?>
</span>
<?php endif; ?>
</a>

<ul class="dropdown-menu dropdown-menu-end">

<?php if (!empty($notifs)): ?>
<?php foreach ($notifs as $n): ?>
<li>
<a class="dropdown-item" href="friends.php">
👤 <?= htmlspecialchars($n['username']) ?> istek gönderdi
</a>
</li>
<?php endforeach; ?>
<?php else: ?>
<li><span class="dropdown-item text-muted">Bildirim yok</span></li>
<?php endif; ?>

</ul>
</li>

<?php endif; ?>

<li class="nav-item">
<a class="nav-link <?= ($active_page ?? '')=='profile'?'active':'' ?>" href="profile.php">
👤 <?= htmlspecialchars($_SESSION['username'] ?? '') ?>
</a>
</li>

<li class="nav-item">
<a class="nav-link" href="logout.php">
🚪 Çıkış
</a>
</li>

</ul>

</div>
</div>
</nav>

</body>
</html>