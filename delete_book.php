<?php
require 'db.php';

$bookId = (int)($_GET['id'] ?? 0);

if ($bookId > 0) {
    $stmt = $conn->prepare('DELETE FROM books WHERE book_id = ?');
    if ($stmt) {
        $stmt->bind_param('i', $bookId);
        $stmt->execute();
        $stmt->close();
    }
}

header('Location: index.php');
exit;
?>
