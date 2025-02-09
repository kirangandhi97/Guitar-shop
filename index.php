<?php
require_once 'auth.php';
if (!$authUser) {
    header("Location: login.php");
    exit;
}

require_once 'Database.php';
require_once 'Guitar.php';

$db = new Database();
$pdo = $db->getPDO();
$guitars = Guitar::getAll($pdo);

require_once "header.php";
?>

<div class="container">
    <h2>Available Guitars</h2>
    <?php if (!empty($guitars)): ?>
        <div class="grid">
            <?php foreach ($guitars as $guitar): ?>
                <div class="guitar">
                    <h2><?php echo htmlspecialchars($guitar->getBrand() . " " . $guitar->getModel()); ?></h2>
                    <p>Type: <?php echo htmlspecialchars($guitar->getType()); ?></p>
                    <p>Price: $<?php echo htmlspecialchars($guitar->getPrice()); ?></p>
                    <p>Quantity: <?php echo htmlspecialchars($guitar->getQuantityInStock()); ?></p>
                    <p><?php echo nl2br(htmlspecialchars($guitar->getDescription())); ?></p>
                    <a class="button" href="add_to_cart.php?guitar_id=<?php echo $guitar->getId(); ?>&quantity=1">Add to Cart</a>
                    <a class="button" href="edit_guitar.php?id=<?php echo $guitar->getId(); ?>">Edit</a>
                    <a class="button button-delete" href="delete_guitar.php?id=<?php echo $guitar->getId(); ?>" onclick="return confirm('Are you sure?');">Delete</a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No guitars available.</p>
    <?php endif; ?>
</div>
</body>

</html>