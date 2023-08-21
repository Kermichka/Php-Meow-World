<?php
session_start();

include('scripts/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Проверка дали потребителят вече съществува
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user) {
        $error = "Потребител с това потребителско име вече съществува.";
    } else {
        // Вмъкване на нов потребител в базата данни
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->execute([$username, $hashed_password]);

        $_SESSION['user_id'] = $pdo->lastInsertId();  // Записваме ID-то на новия потребител
        $_SESSION['username'] = $username;

        header('Location: index.php'); // Пренасочваме към началната страница
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link rel="stylesheet" href="templates/css/styles.css">
</head>
<body>

<div class="navbar">
    <a href="index.php">Начало</a>
    <a href="cart.php">Количка</a>
    <a href="login.php">Вход</a>
</div>

<h1>Регистрация</h1>

<?php if (isset($error)): ?>
    <p style="color: red;"><?php echo $error; ?></p>
<?php endif; ?>

<form method="POST">
    <label for="username">Потребителско име:</label>
    <input type="text" name="username" id="username" required><br>

    <label for="password">Парола:</label>
    <input type="password" name="password" id="password" required><br>

    <button type="submit">Регистрирай се</button>
</form>

<p>Вече имате акаунт? <a href="login.php">Влезте тук</a></p>

</body>
</html>
