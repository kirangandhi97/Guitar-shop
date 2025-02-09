<?php require_once 'auth.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Guitar Shop</title>
    <style>
        * {
            box-sizing: border-box;
        }

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

        .grid {
            display: grid;
            gap: 20px;
            grid-template-columns: repeat(3, 1fr);
        }

        .guitar {
            background-color: #fff;
            padding: 15px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
        }

        .guitar h2 {
            margin-top: 0;
        }

        .button {
            display: inline-block;
            padding: 5px 10px;
            background-color: #333;
            color: #fff;
            text-decoration: none;
            margin-right: 5px;
        }

        .button.button-delete {
            background-color: #f44336;
        }

        .form-wrapper .container {
            width: 600px;
            margin: 30px auto;
            background-color: #fff;
            padding: 20px;
            border: 1px solid #ddd;
        }

        input[type=text],
        input[type=email],
        input[type=password],
        input[type=number],
        textarea {
            width: 100%;
            padding: 8px;
            margin: 5px 0;
        }

        input[type=submit] {
            background-color: #333;
            color: #fff;
            border: none;
            padding: 10px;
            cursor: pointer;
        }

        .message {
            color: green;
        }

        .error {
            color: red;
        }

        a {
            text-decoration: none;
            color: #333;
        }
    </style>
</head>

<body>
    <header>
        <h1>Guitar Shop</h1>
        <nav>
            <?php if ($authUser): ?>
                <a href="index.php">Home</a>
                <a href="add_guitar.php">Add Guitar</a>
                <a href="view_cart.php">View Cart</a>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="registration.php">Register</a>
            <?php endif; ?>
        </nav>
    </header>