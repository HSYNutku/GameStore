<?php
$request_count = 0;
$notifs = [];

if (isset($_SESSION['user_id'])) {

    try {

        $db = Database::getInstance()->getConnection();

        // 🟢 ONLINE DURUM
        $db->prepare("
            UPDATE users 
            SET last_active = NOW() 
            WHERE id=?
        ")->execute([$_SESSION['user_id']]);

        // 👥 ARKADAŞ İSTEK SAYISI
        $stmt = $db->prepare("
            SELECT COUNT(*) 
            FROM friends 
            WHERE receiver_id=? 
            AND status='pending'
        ");

        $stmt->execute([$_SESSION['user_id']]);
        $request_count = (int)$stmt->fetchColumn();

        // 🔔 SON İSTEKLER
        $stmt = $db->prepare("
            SELECT u.username
            FROM friends f
            JOIN users u ON f.sender_id = u.id
            WHERE f.receiver_id=? 
            AND f.status='pending'
            ORDER BY f.id DESC
            LIMIT 5
        ");

        $stmt->execute([$_SESSION['user_id']]);
        $notifs = $stmt->fetchAll();

    } catch(Exception $e){

        $request_count = 0;
        $notifs = [];

    }

}
?>

<!DOCTYPE html>
<html lang="tr">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>
<?= isset($page_title) ? $page_title . ' - ' : '' ?>GameStore
</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<link href="css/style.css" rel="stylesheet">

<style>

body{
    background:linear-gradient(to right,#0f172a,#111827,#1e293b);
    color:white;
}

/* NAVBAR */
.navbar{
    background:#111827;
    padding:8px 0;
    border-bottom:1px solid #334155;
}

/* LOGO */
.navbar-brand{
    font-size:30px;
    font-weight:700;
    color:#60a5fa !important;
    display:flex;
    align-items:center;
    gap:10px;
}

/* MENÜ */
.navbar-nav{
    align-items:center;
    gap:4px;
}

.nav-link{
    color:#e5e7eb !important;
    font-size:15px;
    padding:8px 12px !important;
    border-radius:8px;
    transition:.2s;
    display:flex;
    align-items:center;
    gap:6px;
    white-space:nowrap;
}

.nav-link:hover{
    background:#1e293b;
    color:#60a5fa !important;
}

.nav-link.active{
    background:#334155;
    color:white !important;
}

/* DROPDOWN */
.dropdown-menu{
    background:#1e293b;
    border:1px solid #334155;
    border-radius:10px;
    overflow:hidden;
}

.dropdown-item{
    color:white;
    padding:10px 14px;
    font-size:14px;
}

.dropdown-item:hover{
    background:#334155;
    color:#60a5fa;
}

/* BADGE */
.badge{
    font-size:10px;
}

/* İKON */
.nav-link i{
    font-size:14px;
}

/* MOBİL */
@media(max-width:991px){

.navbar-nav{
    gap:0;
}

.nav-link{
    margin:2px 0;
}

}

</style>

</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark">

<div class="container">

<!-- LOGO -->
<a class="navbar-brand" href="index.php">

<i class="fas fa-gamepad"></i>

GameStore

</a>

<!-- MOBİL -->
<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">

<span class="navbar-toggler-icon"></span>

</button>

<div class="collapse navbar-collapse" id="navbarNav">

<!-- SOL -->
<ul class="navbar-nav me-auto">

<!-- ANA SAYFA -->
<li class="nav-item">

<a class="nav-link <?= ($active_page ?? '')=='home' ? 'active' : '' ?>" href="index.php">

<i class="fas fa-home"></i>

Ana Sayfa

</a>

</li>

<!-- KÜTÜPHANE -->
<li class="nav-item">

<a class="nav-link <?= ($active_page ?? '')=='library' ? 'active' : '' ?>" href="library.php">

<i class="fas fa-book"></i>

Kütüphanem

</a>

</li>

<?php if(isset($_SESSION['user_id'])): ?>

<!-- OYUN EKLE -->
<li class="nav-item">

<a class="nav-link <?= ($active_page ?? '')=='add_game' ? 'active' : '' ?>" href="add_game.php">

<i class="fas fa-plus"></i>

Oyun Ekle

</a>

</li>

<!-- ARKADAŞ -->
<li class="nav-item">

<a class="nav-link <?= ($active_page ?? '')=='friends' ? 'active' : '' ?>" href="friends.php">

<i class="fas fa-user-friends"></i>

Arkadaşlar

<?php if($request_count > 0): ?>

<span class="badge bg-danger">

<?= $request_count ?>

</span>

<?php endif; ?>

</a>

</li>

<!-- SEPET -->
<li class="nav-item">

<a class="nav-link <?= ($active_page ?? '')=='cart' ? 'active' : '' ?>" href="cart.php">

<i class="fas fa-shopping-cart"></i>

Sepetim

</a>

</li>

<?php endif; ?>

<!-- ADMIN -->
<?php if(!empty($_SESSION['is_admin'])): ?>

<li class="nav-item">

<a class="nav-link <?= ($active_page ?? '')=='admin' ? 'active' : '' ?>" href="admin_games.php">

<i class="fas fa-tools"></i>

Admin

</a>

</li>

<?php endif; ?>

<!-- TOPLULUK -->
<li class="nav-item dropdown">

<a class="nav-link dropdown-toggle <?= ($active_page ?? '')=='community' ? 'active' : '' ?>" 
href="#" 
data-bs-toggle="dropdown">

<i class="fas fa-users"></i>

Topluluk

</a>

<ul class="dropdown-menu">

<li>

<a class="dropdown-item" href="community.php">

<i class="fas fa-comments"></i>

Forum

</a>

</li>

<li>

<a class="dropdown-item" href="profiles.php">

<i class="fas fa-id-card"></i>

Profiller

</a>

</li>

</ul>

</li>

</ul>

<!-- SAĞ -->
<ul class="navbar-nav align-items-center">

<?php if(isset($_SESSION['user_id'])): ?>

<!-- BİLDİRİM -->
<li class="nav-item dropdown me-2">

<a class="nav-link position-relative" href="#" data-bs-toggle="dropdown">

<i class="fas fa-bell"></i>

<?php if($request_count > 0): ?>

<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">

<?= $request_count ?>

</span>

<?php endif; ?>

</a>

<ul class="dropdown-menu dropdown-menu-end">

<?php if(!empty($notifs)): ?>

<?php foreach($notifs as $n): ?>

<li>

<a class="dropdown-item" href="friends.php">

<i class="fas fa-user-plus"></i>

<?= htmlspecialchars($n['username']) ?> istek gönderdi

</a>

</li>

<?php endforeach; ?>

<?php else: ?>

<li>

<span class="dropdown-item text-muted">

Bildirim yok

</span>

</li>

<?php endif; ?>

</ul>

</li>

<!-- PROFİL -->
<li class="nav-item">

<a class="nav-link <?= ($active_page ?? '')=='profile' ? 'active' : '' ?>" href="profile.php">

<i class="fas fa-user-circle"></i>

<?= htmlspecialchars($_SESSION['username'] ?? '') ?>

</a>

</li>

<!-- ÇIKIŞ -->
<li class="nav-item">

<a class="nav-link" href="logout.php">

<i class="fas fa-sign-out-alt"></i>

Çıkış

</a>

</li>

<?php else: ?>

<!-- GİRİŞ -->
<li class="nav-item">

<a class="nav-link" href="login.php">

<i class="fas fa-sign-in-alt"></i>

Giriş Yap

</a>

</li>

<!-- KAYIT -->
<li class="nav-item">

<a class="nav-link" href="register.php">

<i class="fas fa-user-plus"></i>

Kayıt Ol

</a>

</li>

<?php endif; ?>

</ul>

</div>
</div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
