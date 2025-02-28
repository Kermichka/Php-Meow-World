<?php
$json = file_get_contents('./products.json');

$products = json_decode($json, true);
if ($products === null) {
    die("Error: Failed to decode JSON file.");
}

include('./scripts/db.php');

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
