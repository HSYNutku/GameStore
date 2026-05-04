<?php
require_once 'config.php';
checkLogin();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name = $_POST['name'];
    $genre = $_POST['genre'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $publisher = $_POST['publisher'];
    $release_date = $_POST['release_date'];

    $target_file = null;

    // 📸 RESİM YÜKLEME
    if (!empty($_FILES['image']['name'])) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','webp'];

        if (!in_array($ext, $allowed)) {
            $error = "Geçersiz dosya türü";
        } else {
            $new_name = uniqid() . "." . $ext;
            $target_file = "uploads/" . $new_name;
            move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
        }
    }

    if (!$error) {
        try {
            $db = Database::getInstance()->getConnection();

            // 🔥 BURASI DÜZELTİLDİ
            $stmt = $db->prepare("
                INSERT INTO games 
                (name, genre, description, price, publisher, release_date, image_url, user_id, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending')
            ");

            $stmt->execute([
                $name,
                $genre,
                $description,
                $price,
                $publisher,
                $release_date,
                $target_file,
                $_SESSION['user_id'] // 🔥 KRİTİK
            ]);

            $success = "Oyun eklendi. Admin onayı bekleniyor.";

        } catch(PDOException $e) {
            $error = $e->getMessage();
        }
    }
}

$page_title = "Oyun Ekle";
$active_page = "add_game";
include 'includes/header.php';
?>

<div class="container mt-4">
<div class="row justify-content-center">
<div class="col-md-8">

<div class="card shadow-lg">
<div class="card-header bg-primary text-white">
<h4 class="mb-0">🎮 Yeni Oyun Ekle</h4>
</div>

<div class="card-body">

<?php if($error): ?>
<div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<?php if($success): ?>
<div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">

<div class="mb-3">
<label class="form-label">Oyun Adı</label>
<input type="text" name="name" class="form-control" required>
</div>

<div class="mb-3">
<label class="form-label">Tür</label>
<input type="text" name="genre" class="form-control" required>
</div>

<div class="mb-3">
<label class="form-label">Açıklama</label>
<textarea name="description" class="form-control" rows="3" required></textarea>
</div>

<div class="mb-3">
<label class="form-label">Fiyat ($)</label>
<input type="number" step="0.01" name="price" class="form-control" required>
</div>

<div class="mb-3">
<label class="form-label">Yayıncı</label>
<input type="text" name="publisher" class="form-control">
</div>

<div class="mb-3">
<label class="form-label">Çıkış Tarihi</label>
<input type="date" name="release_date" class="form-control">
</div>

<div class="mb-3">
<label class="form-label">Oyun Görseli</label>
<input type="file" name="image" class="form-control">
</div>

<button type="submit" class="btn btn-success w-100">
<i class="fas fa-plus"></i> Oyunu Ekle
</button>

</form>

</div>
</div>

</div>
</div>
</div>

<?php include 'includes/footer.php'; ?>