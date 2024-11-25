<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Include the database connection file
require 'db.php';

// Define default sort options
$sort_column = 'created_at';
$sort_order = 'ASC';

if (isset($_GET['sort'])) {
    $allowed_columns = ['title', 'created_at', 'updated_at'];
    if (in_array($_GET['sort'], $allowed_columns)) {
        $sort_column = $_GET['sort'];
    }
    $sort_order = isset($_GET['order']) && $_GET['order'] === 'DESC' ? 'DESC' : 'ASC';
}

// Fetch pages from the database
$query = "SELECT * FROM pages ORDER BY $sort_column $sort_order";
$stmt = $pdo->prepare($query);
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
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        a {
            text-decoration: none;
            color: blue;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>View Pages</h1>
    <table>
        <thead>
            <tr>
                <th><a href="view_pages.php?sort=title&order=<?php echo $sort_order === 'ASC' ? 'DESC' : 'ASC'; ?>">Title</a></th>
                <th><a href="view_pages.php?sort=created_at&order=<?php echo $sort_order === 'ASC' ? 'DESC' : 'ASC'; ?>">Created At</a></th>
                <th><a href="view_pages.php?sort=updated_at&order=<?php echo $sort_order === 'ASC' ? 'DESC' : 'ASC'; ?>">Updated At</a></th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($pages)): ?>
                <?php foreach ($pages as $page): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($page['title']); ?></td>
                        <td><?php echo htmlspecialchars($page['created_at']); ?></td>
                        <td><?php echo htmlspecialchars($page['updated_at']); ?></td>
                        <td>
                            <a href="edit_page.php?id=<?php echo $page['page_id']; ?>">Edit</a> |
                            <a href="delete_page.php?id=<?php echo $page['page_id']; ?>" onclick="return confirm('Are you sure you want to delete this page?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No pages found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
