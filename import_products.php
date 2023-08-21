<?php
// Absolute path to products.json
$json = file_get_contents('/var/www/html/shop/scripts/products.json');

// Decode the JSON file
$products = json_decode($json, true);
if ($products === null) {
    die("Error: Failed to decode JSON file.");
}

// Include the database connection
include('db.php');

// Insert products into the database
foreach ($products as $product) {
    $stmt = $pdo->prepare("INSERT INTO products (name, description, price, quantity, image) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([
        $product['name'],
        $product['description'],
        $product['price'],
        $product['quantity'],
        $product['image']
    ]);
}
echo "Products imported!";
?>
