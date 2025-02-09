<?php
require_once 'auth.php';
if (!$authUser) {
    header("Location: login.php");
    exit;
}

require_once 'Guitar.php';
require_once 'Database.php';

// Verify that an ID is provided
if (!isset($_GET['id'])) {
    die("Guitar ID not provided.");
}

$guitarId = $_GET['id'];
$db = new Database();
$pdo = $db->getPDO();

// Fetch the guitar (optional step for confirmation or logging)
$stmt = $pdo->prepare("SELECT * FROM guitars WHERE guitar_id = :id");
$stmt->execute([':id' => $guitarId]);
$guitarData = $stmt->fetch();

if (!$guitarData) {
    die("Guitar not found.");
}

// Delete the guitar record
$guitar = new Guitar($guitarData);
$guitar->delete($pdo);

// Redirect back to the home page
header("Location: index.php");
exit;
