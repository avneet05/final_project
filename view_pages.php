<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch sorting and search inputs
$sort = $_GET['sort'] ?? 'created_at';
$order = in_array($sort, ['title', 'created_at', 'updated_at']) ? $sort : 'created_at';

$search = $_GET['search'] ?? '';

// Build query dynamically
$query = "SELECT * FROM pages WHERE title LIKE :search ORDER BY $order ASC";
$stmt = $pdo->prepare($query);
$searchTerm = '%' . $search . '%';
$stmt->bindParam(':search', $searchTerm, PDO::PARAM_STR);
$stmt->execute();
$pages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Pages</title>
    <style>
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 8px 12px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f2f2f2; }
        .search-bar, .sort-dropdown { margin-bottom: 15px; }
        .search-bar input { padding: 8px; width: 250px; }
        .sort-dropdown select { padding: 8px; }
        a { text-decoration: none; color: #007bff; }
        a:hover { color: #0056b3; }
    </style>
</head>
<body>
    <h1>View Pages</h1>

    <!-- Search and Sort Section -->
    <form method="GET" class="search-bar">
        <input type="text" name="search" placeholder="Search by title..." value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit">Search</button>
    </form>

    <form method="GET" class="sort-dropdown">
        <label for="sort">Sort by:</label>
        <select name="sort" id="sort" onchange="this.form.submit()">
            <option value="created_at" <?php echo $sort === 'created_at' ? 'selected' : ''; ?>>Created Date</option>
            <option value="updated_at" <?php echo $sort === 'updated_at' ? 'selected' : ''; ?>>Updated Date</option>
            <option value="title" <?php echo $sort === 'title' ? 'selected' : ''; ?>>Title</option>
        </select>
        <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>"> <!-- Preserve search term -->
    </form>

    <!-- Pages Table -->
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Content</th>
                <th>Created At</th>
                <th>Updated At</th>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <th>Actions</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php if (count($pages) > 0): ?>
                <?php foreach ($pages as $page): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($page['title']); ?></td>
                        <td><?php echo htmlspecialchars(substr($page['content'], 0, 50)) . '...'; ?></td>
                        <td><?php echo htmlspecialchars($page['created_at']); ?></td>
                        <td><?php echo htmlspecialchars($page['updated_at']); ?></td>
                        <?php if ($_SESSION['role'] === 'admin'): ?>
                            <td>
                                <a href="edit_pages.php?page_id=<?php echo $page['page_id']; ?>">Edit</a> |
                                <a href="delete.php?page_id=<?php echo $page['page_id']; ?>" onclick="return confirm('Are you sure?');">Delete</a>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">No pages found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <?php if ($_SESSION['role'] === 'admin'): ?>
        <a href="create.php">Create New Page</a>
    <?php endif; ?>
    
    <br>
    <a href="index.php">Back to Home</a>
</body>
</html>
