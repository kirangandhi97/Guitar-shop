<?php
require_once 'auth.php';
if (!$authUser) {
    header("Location: login.php");
    exit;
}

require_once 'Guitar.php';
require_once 'Database.php';

$db = new Database();
$pdo = $db->getPDO();

// Retrieve cart from cookie (stored as JSON)
$cart = isset($_COOKIE['cart']) ? json_decode($_COOKIE['cart'], true) : [];
$cartItems = [];
foreach ($cart as $guitarId => $quantity) {
    $stmt = $pdo->prepare("SELECT * FROM guitars WHERE guitar_id = :id");
    $stmt->execute([':id' => $guitarId]);
    $guitarData = $stmt->fetch();
    if ($guitarData) {
        $guitar = new Guitar($guitarData);
        $cartItems[] = [
            'guitar' => $guitar,
            'quantity' => $quantity
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Your Cart</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 10px 20px;
        }

        nav a {
            color: #fff;
            text-decoration: none;
            margin-right: 15px;
        }

        .container {
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #333;
            color: #fff;
        }

        .button {
            display: inline-block;
            padding: 5px 10px;
            background-color: #333;
            color: #fff;
            text-decoration: none;
        }

        .button.button-delete {
            background-color: #f44336;
        }
    </style>
</head>

<body>
    <header>
        <h1>Your Cart</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="view_cart.php">View Cart</a>
        </nav>
    </header>
    <div class="container">
        <?php if (empty($cartItems)): ?>
            <p>Your cart is empty.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Brand</th>
                        <th>Model</th>
                        <th>Quantity</th>
                        <th>Price per unit</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cartItems as $item):
                        $guitar = $item['guitar'];
                        $quantity = $item['quantity'];
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($guitar->getBrand()); ?></td>
                            <td><?php echo htmlspecialchars($guitar->getModel()); ?></td>
                            <td><?php echo $quantity; ?></td>
                            <td>$<?php echo htmlspecialchars($guitar->getPrice()); ?></td>
                            <td><a class="button button-delete" href="remove_from_cart.php?guitar_id=<?php echo $guitar->getId(); ?>">Remove</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <br />
            <a class="button" href="checkout.php">Proceed to Checkout</a>
        <?php endif; ?>
    </div>
</body>

</html>