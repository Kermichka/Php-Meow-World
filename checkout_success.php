<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Успешна поръчка</title>
    <link rel="stylesheet" href="./templates/css/styles.css"> <!-- Добавяме линк към стиловете -->
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

<h1>Вашата поръчка е успешно изпратена!</h1>
<p>Благодарим ви за покупката. Поръчката ви беше успешно обработена.</p>
<a href="index.php">Продължи пазаруването</a>

</body>
</html>
