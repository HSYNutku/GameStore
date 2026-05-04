<?php
require_once 'config.php';
checkLogin();

$db = Database::getInstance()->getConnection();

$stmt = $db->prepare("
SELECT g.* FROM cart c
JOIN games g ON c.game_id = g.id
WHERE c.user_id = ?
");
$stmt->execute([$_SESSION['user_id']]);
$items = $stmt->fetchAll();

$total = 0;

$page_title = "Sepet";
include 'includes/header.php';
?>

<div class="container mt-4">

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">🛒 Sepetim</h4>
        </div>

        <div class="card-body">

            <?php if (empty($items)): ?>
                <div class="alert alert-warning text-center">
                    Sepetiniz boş 😢
                </div>
            <?php else: ?>

                <table class="table table-dark table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Oyun</th>
                            <th>Fiyat</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach($items as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['name']) ?></td>
                            <td>$<?= number_format($item['price'], 2) ?></td>
                        </tr>
                        <?php $total += $item['price']; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <h4>Toplam: <span class="text-success">$<?= number_format($total, 2) ?></span></h4>

                    <a href="checkout.php" class="btn btn-success btn-lg">
                        💳 Satın Al
                    </a>
                </div>

            <?php endif; ?>

        </div>
    </div>

</div>

<?php include 'includes/footer.php'; ?>