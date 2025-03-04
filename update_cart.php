<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: register.php');
    exit();
}

include('scripts/db.php');

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $action = $_POST['action'];

    // Fetch the current quantity in the cart for the product
    $stmt = $pdo->prepare("SELECT quantity FROM carts WHERE user_id = ? AND product_id = ?");
    $stmt->execute([$user_id, $product_id]);
    $cart_item = $stmt->fetch();

    if ($cart_item) {
        $current_quantity = $cart_item['quantity'];

        if ($action === 'plus') {
            $new_quantity = $current_quantity + 1;

            // Check if the product is available in stock
            $stmt = $pdo->prepare("SELECT quantity FROM products WHERE id = ?");
            $stmt->execute([$product_id]);
            $product = $stmt->fetch();

            if ($product && $product['quantity'] >= $new_quantity) {
                $stmt_update = $pdo->prepare("UPDATE carts SET quantity = ? WHERE user_id = ? AND product_id = ?");
                $stmt_update->execute([$new_quantity, $user_id, $product_id]);
            } else {
                echo "Недостатъчно количество налично.";
            }
        } elseif ($action === 'minus') {
            if ($current_quantity > 1) {
                $new_quantity = $current_quantity - 1;
                $stmt_update = $pdo->prepare("UPDATE carts SET quantity = ? WHERE user_id = ? AND product_id = ?");
                $stmt_update->execute([$new_quantity, $user_id, $product_id]);
            } else {
                // If quantity is 1, remove the item from the cart
                $stmt_delete = $pdo->prepare("DELETE FROM carts WHERE user_id = ? AND product_id = ?");
                $stmt_delete->execute([$user_id, $product_id]);
            }
        }
    }

    header('Location: cart.php');
    exit();
}
?>
