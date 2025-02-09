<?php
require_once 'auth.php';
if (!$authUser) {
    header("Location: login.php");
    exit;
}

require_once 'Guitar.php';
require_once 'Database.php';

$guitarId = $_GET['guitar_id'] ?? null;
$quantity = isset($_GET['quantity']) ? (int)$_GET['quantity'] : 1;

if (!$guitarId) {
    die("No guitar selected.");
}

// Retrieve existing cart from cookie (JSON encoded)
$cart = [];
if (isset($_COOKIE['cart'])) {
    $cart = json_decode($_COOKIE['cart'], true);
}

// Update quantity if guitar already in cart
if (isset($cart[$guitarId])) {
    $cart[$guitarId] += $quantity;
} else {
    $cart[$guitarId] = $quantity;
}

// Save updated cart cookie (expires in 1 day)
setcookie("cart", json_encode($cart), time() + 86400, '/', '', false, true);
header("Location: view_cart.php");
exit;
