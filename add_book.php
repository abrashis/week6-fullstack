<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title    = trim($_POST['title'] ?? '');
    $author   = trim($_POST['author'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $quantity = (int)($_POST['quantity'] ?? 0);

    if ($title !== '' && $author !== '' && $category !== '' && $quantity >= 0) {
        $stmt = $conn->prepare('INSERT INTO books (title, author, category, quantity) VALUES (?, ?, ?, ?)');
        if ($stmt) {
            $stmt->bind_param('sssi', $title, $author, $category, $quantity);
            $stmt->execute();
            $stmt->close();
        }
    }
}

header('Location: index.php');
exit;
?>
