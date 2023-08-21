<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: register.php');
    exit();
}

include('scripts/db.php');

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($_FILES['profile_picture']['type'], $allowed_types)) {
            if ($_FILES['profile_picture']['size'] <= 2 * 1024 * 1024) {
                $profile_picture = 'uploads/' . basename($_FILES['profile_picture']['name']);

                if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $profile_picture)) {
                    $stmt = $pdo->prepare("UPDATE users SET profile_picture = ? WHERE id = ?");
                    $stmt->execute([$profile_picture, $user_id]);

                    header("Location: profile.php");
                    exit();
                } else {
                    $error = "Не успяхме да качим снимката.";
                }
            } else {
                $error = "Снимката е твърде голяма. Максимален размер: 2MB.";
            }
        } else {
            $error = "Поддържаме само JPEG, PNG и GIF формати.";
        }
    } else {
        $error = "Моля, изберете снимка за качване.";
    }
}
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Профил</title>
    <link rel="stylesheet" href="./templates/css/styles.css">
</head>
<body>

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

<h1>Вашият Профил</h1>

<?php if (isset($error)): ?>
    <p style="color: red;"><?php echo $error; ?></p>
<?php endif; ?>

<?php if ($user): ?>
    <div class="profile-info">
        <img src="<?php echo $user['profile_picture'] ? $user['profile_picture'] : 'default_profile.jpg'; ?>" alt="Profile Picture" width="150" height="150">
        <form method="POST" enctype="multipart/form-data">
            <label for="profile_picture">Промени снимка на профила:</label>
            <input type="file" name="profile_picture" id="profile_picture">
            <button type="submit">Запази промени</button>
        </form>
    </div>
<?php endif; ?>

</body>
</html>
