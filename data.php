<?php

require_once 'db.php';

// Fetch categories from database
try {
    $stmt = $pdo->query("SELECT slug, name FROM categories");
    $categories = [];
    while ($row = $stmt->fetch()) {
        $categories[$row['slug']] = $row['name'];
    }
} catch (PDOException $e) {
    $categories = []; // Fallback
}

function getArticlesByCategory($category) {
    global $pdo;
    try {
        if ($category === 'all') {
            $stmt = $pdo->query("SELECT * FROM articles ORDER BY date DESC");
        } else {
            $stmt = $pdo->prepare("SELECT * FROM articles WHERE category = ? ORDER BY date DESC");
            $stmt->execute([$category]);
        }
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        return [];
    }
}

function getArticleById($id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT * FROM articles WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        return null;
    }
}
?>
