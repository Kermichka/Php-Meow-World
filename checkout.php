<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: register.php');
    exit();
}

include('scripts/db.php');

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT carts.product_id, carts.quantity AS cart_quantity, products.quantity AS product_quantity
                       FROM carts
                       JOIN products ON carts.product_id = products.id
                       WHERE carts.user_id = ?");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($cart_items as $item) {
        if ($item['cart_quantity'] > $item['product_quantity']) {
            echo "Няма достатъчно количество за продукта: " . $item['product_id'];
            exit();
        }
    }

    foreach ($cart_items as $item) {
        $new_quantity = $item['product_quantity'] - $item['cart_quantity'];
        $product_id = $item['product_id'];

        $stmt_update = $pdo->prepare("UPDATE products SET quantity = ? WHERE id = ?");
        $stmt_update->execute([$new_quantity, $product_id]);
    }

    $stmt_clear_cart = $pdo->prepare("DELETE FROM carts WHERE user_id = ?");
    $stmt_clear_cart->execute([$user_id]);

    header('Location: checkout_success.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Поръчка</title>
</head>
<body>

<h1>Поръчка</h1>

<?php if ($cart_items): ?>
    <ul>
        <?php foreach ($cart_items as $item): ?>
            <li>
                Продукт: <?php echo $item['product_id']; ?> - Количество: <?php echo $item['cart_quantity']; ?>
            </li>
        <?php endforeach; ?>
    </ul>
    <form method="POST">
        <button type="submit">Поръчай</button>
    </form>
<?php else: ?>
    <p>Количката е празна.</p>
<?php endif; ?>

</body>
</html>
