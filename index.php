<?php
require 'db.php';

$category = trim($_GET['category'] ?? '');
$books = [];

if ($category !== '') {
    $stmt = $conn->prepare('SELECT book_id, title, author, category, quantity FROM books WHERE category LIKE ? ORDER BY title');
    if ($stmt) {
        $like = '%' . $category . '%';
        $stmt->bind_param('s', $like);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result) {
            $books = $result->fetch_all(MYSQLI_ASSOC);
        }
        $stmt->close();
    }
} else {
    $result = $conn->query('SELECT book_id, title, author, category, quantity FROM books ORDER BY title');
    if ($result) {
        $books = $result->fetch_all(MYSQLI_ASSOC);
    }
}

function e($value)
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Library Manager</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="page">
    <header class="hero">
      <div>
        <p class="eyebrow">PHP + MySQL</p>
        <h1>Library Management System</h1>
        <p class="lede">Add books, search by category, and delete entries with a single page view.</p>
      </div>
    </header>

    <main class="grid">
      <section class="card">
        <h2>Add a Book</h2>
        <form class="stack" action="add_book.php" method="post">
          <label>Title<input type="text" name="title" placeholder="Database Systems" required></label>
          <label>Author<input type="text" name="author" placeholder="Elmasri" required></label>
          <label>Category<input type="text" name="category" placeholder="Education" required></label>
          <label>Quantity<input type="number" name="quantity" min="0" value="1" required></label>
          <button type="submit">Add Book</button>
        </form>
      </section>

      <section class="card">
        <h2>Search</h2>
        <form class="stack row" method="get">
          <input type="text" name="category" value="<?php echo e($category); ?>" placeholder="Category keyword">
          <button type="submit">Search</button>
          <a class="link" href="index.php">Clear</a>
        </form>
        <p class="hint">Showing <?php echo count($books); ?> result(s)<?php echo $category !== '' ? ' for "' . e($category) . '"' : ''; ?>.</p>
      </section>

      <section class="card wide">
        <h2>Books</h2>
        <?php if (empty($books)): ?>
          <p class="muted">No books found. Add one above.</p>
        <?php else: ?>
          <div class="table">
            <div class="table-head">
              <span>Title</span><span>Author</span><span>Category</span><span>Qty</span><span>Actions</span>
            </div>
            <?php foreach ($books as $book): ?>
              <div class="table-row">
                <span><?php echo e($book['title']); ?></span>
                <span><?php echo e($book['author']); ?></span>
                <span><?php echo e($book['category']); ?></span>
                <span><?php echo (int)$book['quantity']; ?></span>
                <span>
                  <a class="delete" href="delete_book.php?id=<?php echo (int)$book['book_id']; ?>" onclick="return confirm('Delete this book?');">Delete</a>
                </span>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </section>
    </main>
  </div>
</body>
</html>
