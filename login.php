<?php
require_once 'db.php';
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: index.php");
            exit;
        } else {
            $error = "Invalid email or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - CNN Clone</title>
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
    <h2>Sign In</h2>
    <?php if ($error): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>
    <form method="POST">
        <div class="form-group">
            <label>Email Address</label>
            <input type="email" name="email" required>
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit" class="btn-submit">Sign In</button>
    </form>
    <a href="signup.php" class="auth-link">Don't have an account? Sign Up</a>
</div>

</body>
</html>
