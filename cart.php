<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: register.php');
    exit();
}

include('scripts/db.php');

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT products.id, products.name, products.price, carts.quantity
                       FROM carts 
                       JOIN products ON carts.product_id = products.id 
                       WHERE carts.user_id = ?");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Количка</title>
    <link rel="stylesheet" href="./templates/css/styles.css">
</head>
<body>

<!-- Навигационен бар -->
<div class="navbar">
    <a href="index.php">Начало</a>
    <a href="cart.php">Количка</a>
    <?php if (!isset($_SESSION['user_id'])): ?>
        <a href="register.php">Регистрация</a>
        <a href="login.php">Вход</a>
    <?php else: ?>
        <a href="profile.php">Профил</a>
        <a href="logout.php">Изход</a>
    <?php endif; ?>
</div>

<h1>Твоята количка</h1>

<?php if ($cart_items): ?>
    <ul class="cart-items">
        <?php foreach ($cart_items as $item): ?>
            <li class="cart-item">
                <div class="product-info">
                    <h2><?php echo htmlspecialchars($item['name']); ?></h2>
                    <p>Цена: <?php echo number_format($item['price'] / 100, 2); ?> лв.</p>
                    <p>Количество: <?php echo $item['quantity']; ?></p>
                </div>
                <div class="cart-actions">
                    <form action="update_cart.php" method="POST" style="display:inline;">
                        <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                        <button type="submit" name="action" value="minus">-</button>
                        <button type="submit" name="action" value="plus">+</button>
                    </form>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>

    <form action="checkout.php" method="POST" class="checkout-form">
        <button type="submit">Поръчай</button>
    </form>

<?php else: ?>
    <p>Количката е празна.</p>
<?php endif; ?>

</body>
</html>
