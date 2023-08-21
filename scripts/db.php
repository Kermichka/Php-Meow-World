<?php
// Пътят към вашата база данни (SQLite)
$db_file = '/var/www/html/shop/data/shop.db';

// Създаване на връзка с базата данни (SQLite)
try {
    $pdo = new PDO('sqlite:' . $db_file);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // SQL заявка за създаване на таблица "products"
    $pdo->exec("CREATE TABLE IF NOT EXISTS products (
        id INTEGER PRIMARY KEY,
        name TEXT,
        description TEXT,
        price INTEGER,
        quantity INTEGER,
        image TEXT
    );");

    // SQL заявка за създаване на таблица "users"
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY,
        username TEXT,
        password TEXT,
        profile_picture TEXT
    );");

    // SQL заявка за създаване на таблица "carts"
    $pdo->exec("CREATE TABLE IF NOT EXISTS carts (
        id INTEGER PRIMARY KEY,
        user_id INTEGER,
        product_id INTEGER,
        quantity INTEGER,
        FOREIGN KEY(user_id) REFERENCES users(id),
        FOREIGN KEY(product_id) REFERENCES products(id)
    );");

} catch (PDOException $e) {
    echo "Грешка при свързване с базата данни: " . $e->getMessage();
}
?>
