<?php
require_once 'auth.php';
if (!$authUser) {
    header("Location: login.php");
    exit;
}

require_once 'Order.php';
require_once 'OrderItem.php';
require_once 'Guitar.php';
require_once 'Database.php';

// Establish database connection.
$db = new Database();
$pdo = $db->getPDO();

// Retrieve the cart from the cookie (stored as JSON).
$cart = isset($_COOKIE['cart']) ? json_decode($_COOKIE['cart'], true) : [];

// If the cart is empty, display a message.
if (empty($cart)) {
    die("Your cart is empty. <a href='index.php'>Return to shopping</a>");
}

// Initialize variables.
$errors = [];
$success = "";
$total = 0;

// Process the order when the form is submitted.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Simulated logged-in user ID.
    $userId = $authUser['user_id'];

    // Create a new order instance.
    $order = new Order([
        'user_id' => $userId,
        'status'  => 'pending'
    ]);

    // Loop through cart items to add them to the order.
    foreach ($cart as $guitarId => $quantity) {
        // Retrieve the guitar details.
        $stmt = $pdo->prepare("SELECT * FROM guitars WHERE guitar_id = :id");
        $stmt->execute([':id' => $guitarId]);
        $guitarData = $stmt->fetch();

        if (!$guitarData) {
            $errors[] = "Guitar with ID $guitarId not found.";
            continue;
        }

        $guitar = new Guitar($guitarData);

        // Validate available stock.
        if ($quantity > $guitar->getQuantityInStock()) {
            $errors[] = "Insufficient stock for " . $guitar->getBrand() . " " . $guitar->getModel() . ".";
            continue;
        }

        // Create an order item for this guitar.
        $orderItem = new OrderItem([
            'guitar_id'        => $guitar->getId(),
            'quantity'         => $quantity,
            'price_at_purchase' => $guitar->getPrice()
        ]);

        // Add the order item to the order.
        $order->addItem($orderItem);
    }

    // If no errors, attempt to save the order.
    if (empty($errors)) {
        $pdo->beginTransaction();
        try {
            // Save the order and its items.
            $order->save($pdo);

            // Update the inventory for each guitar purchased.
            foreach ($cart as $guitarId => $quantity) {
                $stmt = $pdo->prepare("UPDATE guitars SET quantity_in_stock = quantity_in_stock - :quantity WHERE guitar_id = :id");
                $stmt->execute([
                    ':quantity' => $quantity,
                    ':id'       => $guitarId
                ]);
            }
            $pdo->commit();

            // Clear the cart cookie.
            setcookie("cart", "", time() - 3600, '/', '', false, true);
            $success = "Order placed successfully!";
        } catch (Exception $e) {
            $pdo->rollBack();
            $errors[] = "Error processing order: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Checkout - Guitar Shop</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 800px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ddd;
        }

        h2,
        h3 {
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .button {
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            border: none;
            cursor: pointer;
            text-decoration: none;
        }

        .message {
            color: green;
        }

        .error {
            color: red;
        }

        .back-link {
            margin-top: 10px;
            display: inline-block;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Checkout</h2>

        <!-- Display errors, if any -->
        <?php if (!empty($errors)): ?>
            <div class="error">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- If order was placed successfully, show the success message -->
        <?php if ($success): ?>
            <div class="message">
                <p><?php echo htmlspecialchars($success); ?></p>
            </div>
            <p><a class="button" href="index.php">Continue Shopping</a></p>

            <!-- Otherwise, display the order summary and checkout confirmation form -->
        <?php else: ?>
            <h3>Order Summary</h3>
            <table>
                <tr>
                    <th>Guitar</th>
                    <th>Quantity</th>
                    <th>Price per Unit</th>
                    <th>Subtotal</th>
                </tr>
                <?php
                // Display the cart items and calculate the total.
                foreach ($cart as $guitarId => $quantity) {
                    $stmt = $pdo->prepare("SELECT * FROM guitars WHERE guitar_id = :id");
                    $stmt->execute([':id' => $guitarId]);
                    $guitarData = $stmt->fetch();
                    if ($guitarData) {
                        $guitar = new Guitar($guitarData);
                        $subtotal = $guitar->getPrice() * $quantity;
                        $total += $subtotal;
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($guitar->getBrand() . " " . $guitar->getModel()) . "</td>";
                        echo "<td>" . htmlspecialchars($quantity) . "</td>";
                        echo "<td>$" . number_format($guitar->getPrice(), 2) . "</td>";
                        echo "<td>$" . number_format($subtotal, 2) . "</td>";
                        echo "</tr>";
                    }
                }
                echo "<tr><th colspan='3'>Total</th><th>$" . number_format($total, 2) . "</th></tr>";
                ?>
            </table>

            <!-- Checkout confirmation form -->
            <form method="post" action="checkout.php">
                <!-- In a real application, you might include additional fields for shipping and payment details here -->
                <input type="submit" class="button" value="Place Order">
            </form>
            <p><a class="back-link" href="view_cart.php">Back to Cart</a></p>
        <?php endif; ?>
    </div>
</body>

</html>