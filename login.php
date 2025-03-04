<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include('scripts/db.php');

    // Получаваме въведените данни
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Взимаме потребителя от базата данни
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Ако паролата е вярна, създаваме сесия за потребителя
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        // Пренасочваме към началната страница
        header('Location: index.php');
        exit();
    } else {
        $error = "Невалидно потребителско име или парола.";
    }
}
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход</title>
    <link rel="stylesheet" href="templates/css/styles.css">
</head>
<body>

<div class="navbar">
    <a href="index.php">Начало</a>
    <a href="cart.php">Количка</a>
    <a href="register.php">Регистрация</a>
</div>

<h1>Вход</h1>

<?php if (isset($error)): ?>
    <p style="color: red;"><?php echo $error; ?></p>
<?php endif; ?>

<form method="POST">
    <label for="username">Потребителско име:</label>
    <input type="text" name="username" id="username" required><br>

    <label for="password">Парола:</label>
    <input type="password" name="password" id="password" required><br>

    <button type="submit">Влез</button>
</form>

<p>Нямате акаунт? <a href="register.php">Регистрирайте се</a></p>

</body>
</html>
