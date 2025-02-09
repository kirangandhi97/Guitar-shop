<?php
require_once 'User.php';
require_once 'Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $errors = [];
    if (empty($username)) $errors[] = 'Username is required';
    if (empty($email)) $errors[] = 'Email is required';
    if (empty($password)) $errors[] = 'Password is required';

    if (empty($errors)) {
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        $user = new User([
            'username' => $username,
            'email' => $email,
            'password_hash' => $passwordHash
        ]);
        $db = new Database();
        $pdo = $db->getPDO();
        $user->save($pdo);
        echo "<p style='color: green;'>Registration successful!</p>";
    } else {
        foreach ($errors as $error) {
            echo "<p class='error'>$error</p>";
        }
    }
}

require_once "header.php";
?>
<div class="form-wrapper">
    <div class="container">
        <h2>Register</h2>
        <form method="post" action="registration.php">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>

            <input type="submit" value="Register">
        </form>
    </div>
</div>
</body>

</html>