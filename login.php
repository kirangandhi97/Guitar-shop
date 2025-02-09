<?php
require_once 'User.php';
require_once 'Database.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $db = new Database();
    $pdo = $db->getPDO();
    $user = User::findByUsername($pdo, $username);

    if ($user && password_verify($password, $user->getPasswordHash())) {
        // Authentication successful.
        // Create a simple auth token. (In production, consider using a robust JWT or similar solution.)
        $tokenData = [
            'user_id'   => $user->getId(),
            'username'  => $user->getUsername(),
            'issued_at' => time()
        ];
        $token = base64_encode(json_encode($tokenData));

        // Set token in a secure HTTP-only cookie for 1 hour.
        setcookie("auth_token", $token, time() + 3600, '/', '', false, true);

        // Redirect to index.php after successful login.
        header("Location: index.php");
        exit;
    } else {
        $message = "Invalid username or password.";
    }
}

require_once "header.php";
?>

<div class="form-wrapper">
    <div class="container">
        <h2>Login</h2>
        <?php if ($message): ?>
            <p class="message"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>
        <form method="post" action="login.php">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>

            <input type="submit" value="Login">
        </form>
    </div>
</div>
</body>

</html>