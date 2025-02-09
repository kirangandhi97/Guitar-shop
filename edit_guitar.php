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

// Verify that an ID is provided
if (!isset($_GET['id'])) {
    die("Guitar ID not provided.");
}

$guitarId = $_GET['id'];

// Fetch existing guitar data
$stmt = $pdo->prepare("SELECT * FROM guitars WHERE guitar_id = :id");
$stmt->execute([':id' => $guitarId]);
$guitarData = $stmt->fetch();

if (!$guitarData) {
    die("Guitar not found.");
}

$guitar = new Guitar($guitarData);

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
        // Update guitar data
        $guitar = new Guitar([
            'guitar_id'         => $guitarId,
            'brand'             => $brand,
            'model'             => $model,
            'type'              => $type,
            'price'             => $price,
            'quantity_in_stock' => $quantity,
            'description'       => $description
        ]);
        $guitar->save($pdo);
        $message = "Guitar updated successfully!";
    }
}


require_once "header.php";
?>
<div class="form-wrapper">
    <div class="container">
        <h2>Edit Guitar</h2>
        <?php if ($message): ?>
            <p class="<?php echo strpos($message, 'successfully') !== false ? 'message' : 'error'; ?>">
                <?php echo $message; ?>
            </p>
        <?php endif; ?>
        <form method="post" action="edit_guitar.php?id=<?php echo $guitar->getId(); ?>">
            <label for="brand">Brand:</label>
            <input type="text" name="brand" id="brand" value="<?php echo htmlspecialchars($guitar->getBrand()); ?>" required>

            <label for="model">Model:</label>
            <input type="text" name="model" id="model" value="<?php echo htmlspecialchars($guitar->getModel()); ?>" required>

            <label for="type">Type:</label>
            <input type="text" name="type" id="type" value="<?php echo htmlspecialchars($guitar->getType()); ?>" required>

            <label for="price">Price:</label>
            <input type="number" step="0.01" name="price" id="price" value="<?php echo htmlspecialchars($guitar->getPrice()); ?>" required>

            <label for="quantity">Quantity in Stock:</label>
            <input type="number" name="quantity" id="quantity" value="<?php echo htmlspecialchars($guitar->getQuantityInStock()); ?>" required>

            <label for="description">Description:</label>
            <textarea name="description" id="description"><?php echo htmlspecialchars($guitar->getDescription()); ?></textarea>

            <input type="submit" value="Update Guitar">
        </form>
    </div>
</div>
</body>

</html>