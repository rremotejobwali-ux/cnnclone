<?php
require_once 'db.php';
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($email) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        // Check if email exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
        $stmt->execute([$email, $username]);
        if ($stmt->fetch()) {
            $error = "Username or email already exists.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            if ($stmt->execute([$username, $email, $hashed_password])) {
                header("Location: login.php");
                exit;
            } else {
                $error = "Something went wrong. Please try again.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - CNN Clone</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .auth-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 30px;
            border: 1px solid #ddd;
            border-radius: 8px;
            text-align: center;
        }
        .auth-container h2 { margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; text-align: left; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; }
        .btn-submit { width: 100%; padding: 10px; background: #cc0000; color: white; border: none; border-radius: 4px; font-size: 16px; cursor: pointer; font-weight: bold; }
        .btn-submit:hover { background: #990000; }
        .error { color: red; margin-bottom: 15px; }
        .auth-link { margin-top: 15px; display: block; font-size: 14px; }
    </style>
</head>
<body>

<header>
    <div class="top-bar">
        <a href="index.php" class="logo">CNN</a>
    </div>
</header>

<div class="auth-container">
    <h2>Create Account</h2>
    <?php if ($error): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>
    <form method="POST">
        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" required>
        </div>
        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit" class="btn-submit">Sign Up</button>
    </form>
    <a href="login.php" class="auth-link">Already have an account? Sign In</a>
</div>

</body>
</html>
