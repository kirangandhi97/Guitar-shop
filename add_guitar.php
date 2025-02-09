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
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize form data
    $brand       = trim($_POST['brand'] ?? '');
    $model       = trim($_POST['model'] ?? '');
    $type        = trim($_POST['type'] ?? '');
    $price       = trim($_POST['price'] ?? '');
    $quantity    = trim($_POST['quantity'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if (empty($brand) || empty($model) || empty($type) || empty($price) || empty($quantity)) {
        $message = "Please fill in all required fields.";
    } else {
        // Create and save the new guitar
        $guitar = new Guitar([
            'brand'            => $brand,
            'model'            => $model,
            'type'             => $type,
            'price'            => $price,
            'quantity_in_stock' => $quantity,
            'description'      => $description
        ]);
        $guitar->save($pdo);
        $message = "Guitar added successfully!";
    }
}

require_once "header.php";
?>
<div class="form-wrapper">
    <div class="container">
        <h2>Add New Guitar</h2>
        <?php if ($message): ?>
            <p class="<?php echo strpos($message, 'successfully') !== false ? 'message' : 'error'; ?>">
                <?php echo $message; ?>
            </p>
        <?php endif; ?>
        <form method="post" action="add_guitar.php">
            <label for="brand">Brand:</label>
            <input type="text" name="brand" id="brand" required>

            <label for="model">Model:</label>
            <input type="text" name="model" id="model" required>

            <label for="type">Type:</label>
            <input type="text" name="type" id="type" required>

            <label for="price">Price:</label>
            <input type="number" step="0.01" name="price" id="price" required>

            <label for="quantity">Quantity in Stock:</label>
            <input type="number" name="quantity" id="quantity" required>

            <label for="description">Description:</label>
            <textarea name="description" id="description"></textarea>

            <input type="submit" value="Add Guitar">
        </form>
    </div>
</div>
</body>

</html>