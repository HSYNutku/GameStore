<?php
require_once 'config.php';
checkLogin();

$db = Database::getInstance()->getConnection();

// Sepet ürünleri
$stmt = $db->prepare("
SELECT g.* FROM cart c
JOIN games g ON c.game_id = g.id
WHERE c.user_id = ?
");
$stmt->execute([$_SESSION['user_id']]);
$items = $stmt->fetchAll();

$total = 0;
foreach($items as $item){
    $total += $item['price'];
}

// ORDER oluştur
$stmt = $db->prepare("INSERT INTO orders (user_id, total_price) VALUES (?, ?)");
$stmt->execute([$_SESSION['user_id'], $total]);

$order_id = $db->lastInsertId();

// 🔥 HER OYUN İÇİN
foreach($items as $item){

    // 🔥 1. ZATEN SAHİP Mİ?
    $check = $db->prepare("
    SELECT id FROM library 
    WHERE user_id=? AND game_id=?
    ");
    $check->execute([$_SESSION['user_id'], $item['id']]);

    if(!$check->fetch()){
        // 🔥 2. KÜTÜPHANEYE EKLE
        $db->prepare("
        INSERT INTO library (user_id, game_id)
        VALUES (?, ?)
        ")->execute([$_SESSION['user_id'], $item['id']]);
    }

    // 🔥 3. ORDER ITEMS
    $stmt = $db->prepare("
    INSERT INTO order_items (order_id, game_id, price)
    VALUES (?, ?, ?)
    ");
    $stmt->execute([$order_id, $item['id'], $item['price']]);
}

// SEPETİ TEMİZLE
$db->prepare("DELETE FROM cart WHERE user_id = ?")->execute([$_SESSION['user_id']]);

$page_title = "Satın Alma";
include 'includes/header.php';
?>

<div class="container mt-5">
<div class="row justify-content-center">
<div class="col-md-6">

<div class="card text-center shadow-lg border-0">
<div class="card-body p-5">

<div class="mb-4">
<i class="fas fa-check-circle text-success" style="font-size:60px;"></i>
</div>

<h2 class="mb-3">Satın Alma Başarılı 🎉</h2>

<p class="text-muted">
Oyun(lar) başarıyla satın alındı.  
Artık kütüphanende!
</p>

<div class="d-grid gap-2 mt-4">

<a href="index.php" class="btn btn-primary">
🏠 Ana Sayfaya Dön
</a>

<a href="library.php" class="btn btn-outline-light">
🎮 Kütüphaneye Git
</a>

</div>

</div>
</div>

</div>
</div>
</div>

<?php include 'includes/footer.php'; ?>
