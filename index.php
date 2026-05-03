<?php
require_once 'config.php';
checkLogin();

$db = Database::getInstance()->getConnection();
$games = [];
$error = '';

try {

    // 🔥 SADECE ONAYLI OYUNLAR
    $query = "SELECT * FROM games WHERE status = 'approved'";
    $params = [];

    // 🔍 Arama
    if (!empty($_GET['search'])) {
        $query .= " AND name LIKE ?";
        $params[] = "%" . $_GET['search'] . "%";
    }

    // 🎮 Tür filtre
    if (!empty($_GET['genre'])) {
        $query .= " AND genre = ?";
        $params[] = $_GET['genre'];
    }

    // 💰 Fiyat filtre
    if (!empty($_GET['price_range'])) {
        list($min, $max) = explode('-', $_GET['price_range']);

        if ($max == '+') {
            $query .= " AND price >= ?";
            $params[] = $min;
        } else {
            $query .= " AND price BETWEEN ? AND ?";
            $params[] = $min;
            $params[] = $max;
        }
    }

    $query .= " ORDER BY name";

    $stmt = $db->prepare($query);
    $stmt->execute($params);
    $games = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    $error = $e->getMessage();
}

$page_title = 'Ana Sayfa';
$active_page = 'home';
include 'includes/header.php';
?>

<div class="container mt-4">

    <!-- 🔍 FİLTRE -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">

                <div class="col-md-4">
                    <input type="text" name="search" class="form-control"
                        placeholder="Oyun ara..."
                        value="<?= $_GET['search'] ?? '' ?>">
                </div>

                <div class="col-md-3">
                    <select name="genre" class="form-select">
                        <option value="">Tüm Türler</option>
                        <?php
                        $genres = $db->query("SELECT DISTINCT genre FROM games WHERE status='approved'")->fetchAll(PDO::FETCH_COLUMN);
                        foreach ($genres as $genre):
                        ?>
                        <option value="<?= $genre ?>" <?= (($_GET['genre'] ?? '') == $genre ? 'selected' : '') ?>>
                            <?= $genre ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <select name="price_range" class="form-select">
                        <option value="">Tüm fiyatlar</option>
                        <option value="0-50">0-50</option>
                        <option value="50-100">50-100</option>
                        <option value="100-200">100-200</option>
                        <option value="200+">200+</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <button class="btn btn-primary w-100">Filtrele</button>
                </div>

            </form>
        </div>
    </div>

    <!-- 🎮 OYUNLAR -->
    <h2>Oyunlar</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <div class="row row-cols-1 row-cols-md-3 g-4">

        <?php foreach ($games as $game): ?>
        <div class="col">
            <div class="card h-100">

                <?php if (!empty($game['image_url'])): ?>
                <img src="<?= $game['image_url'] ?>" class="card-img-top">
                <?php endif; ?>

                <div class="card-body">
                    <h5 class="card-title">
                        <a href="game_details.php?id=<?= $game['id'] ?>">
                            <?= htmlspecialchars($game['name']) ?>
                        </a>
                    </h5>

                    <p>
                        Tür: <?= $game['genre'] ?><br>
                        Fiyat: $<?= $game['price'] ?>
                    </p>

                    <a href="game_details.php?id=<?= $game['id'] ?>" class="btn btn-primary btn-sm">
                        Detay
                    </a>
                </div>

            </div>
        </div>
        <?php endforeach; ?>

    </div>

</div>

<?php include 'includes/footer.php'; ?>