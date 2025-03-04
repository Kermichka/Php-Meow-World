<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: register.php');
    exit();
}

include('scripts/db.php');

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT carts.product_id, carts.quantity AS cart_quantity, products.quantity AS product_quantity, products.name AS product_name
                       FROM carts
                       JOIN products ON carts.product_id = products.id
                       WHERE carts.user_id = ?");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check for zero quantities in the cart
    foreach ($cart_items as $item) {
        if ($item['cart_quantity'] <= 0) {
            $_SESSION['error_message'] = "Продуктът '" . htmlspecialchars($item['product_name']) . "' не може да бъде поръчан, защото количеството е нула.";
            header('Location: cart.php');
            exit();
        }

        if ($item['cart_quantity'] > $item['product_quantity']) {
            $_SESSION['error_message'] = "Няма достатъчно количество за продукта: " . htmlspecialchars($item['product_name']);
            header('Location: cart.php');
            exit();
        }
    }

    // Update product quantities and clear the cart
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
    <link rel="stylesheet" href="./templates/css/styles.css">
</head>
<body>

<h1>Поръчка</h1>

<?php if (isset($_SESSION['error_message'])): ?>
    <div class="error-message">
        <?php echo htmlspecialchars($_SESSION['error_message']); ?>
        <?php unset($_SESSION['error_message']); // Clear message after displaying ?>
    </div>
<?php endif; ?>

<?php if ($cart_items): ?>
    <ul class="cart-items">
        <?php foreach ($cart_items as $item): ?>
            <li class="cart-item">
                <div class="product-info">
                    <h2><?php echo htmlspecialchars($item['product_name']); ?></h2>
                    <p>Количество: <?php echo $item['cart_quantity']; ?></p>
                </div>
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
