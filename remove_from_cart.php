<?php
$guitarId = $_GET['guitar_id'] ?? null;
if (!$guitarId) {
    die("No guitar specified.");
}

$cart = isset($_COOKIE['cart']) ? json_decode($_COOKIE['cart'], true) : [];
if (isset($cart[$guitarId])) {
    unset($cart[$guitarId]);
}
setcookie("cart", json_encode($cart), time() + 86400, '/', '', false, true);
header("Location: view_cart.php");
exit;
