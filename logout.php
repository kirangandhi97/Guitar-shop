<?php
// Expire the auth_token cookie
setcookie("auth_token", "", time() - 3600, '/', '', false, true);

// Redirect to login page
header("Location: login.php");
exit;
