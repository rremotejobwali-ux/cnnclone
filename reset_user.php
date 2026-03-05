<?php
require_once 'db.php';

$email = 'rafkay@gmail.com';
$password = '09876';
$username = 'Rafkay';

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Check if user exists
$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

if ($user) {
    // Update password
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
    $stmt->execute([$hashed_password, $email]);
    echo "User password updated successfully.";
} else {
    // Create user
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    try {
        $stmt->execute([$username, $email, $hashed_password]);
        echo "User created successfully.";
    } catch (PDOException $e) {
        echo "Error creating user: " . $e->getMessage();
    }
}
?>
