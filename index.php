<?php
session_start();

// Връзка с базата данни
include('scripts/db.php');

// Извличане на продукти от базата данни
$stmt = $pdo->query("SELECT * FROM products");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Магазин</title>
    <link rel="stylesheet" href="./templates/css/styles.css">
</head>
<body>

<!-- Горен навигационен бар -->
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

<h1>Продукти</h1>

<div class="products">
    <?php foreach ($products as $product): ?>
        <div class="product">
            <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" width="200" height="200">
            <h2><?php echo $product['name']; ?></h2>
            <p><?php echo $product['description']; ?></p>
            <p>Цена: <?php echo number_format($product['price'] / 100, 2); ?> лв.</p>
            <p>Наличност: <?php echo $product['quantity']; ?></p>
            <form method="POST" action="add_to_cart.php">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                <button type="submit">Добави в количката</button>
            </form>
        </div>
    <?php endforeach; ?>
</div>

</body>
</html>
