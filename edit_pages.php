<?php
session_start();
require 'db.php';

// Ensure the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo "Access Denied: Only admins can edit pages.";
    exit();
}

// Check if a page ID is provided
if (!isset($_GET['page_id']) || empty($_GET['page_id'])) {
    echo "Error: Page ID is missing.";
    exit();
}

$page_id = intval($_GET['page_id']);

// Fetch the page details to pre-fill the form
$query = "SELECT * FROM pages WHERE page_id = :page_id LIMIT 1";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':page_id', $page_id, PDO::PARAM_INT);
$stmt->execute();
$page = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$page) {
    echo "Error: Page not found.";
    exit();
}

// Handle form submission to update the page
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';

    if (!empty($title) && !empty($content)) {
        $update_query = "UPDATE pages SET title = :title, content = :content, updated_at = NOW() WHERE page_id = :page_id";
        $update_stmt = $pdo->prepare($update_query);
        $update_stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $update_stmt->bindParam(':content', $content, PDO::PARAM_STR);
        $update_stmt->bindParam(':page_id', $page_id, PDO::PARAM_INT);

        if ($update_stmt->execute()) {
            // Redirect to home page after editing
            header("Location: index.php");
            exit();
        } else {
            $message = "Error: Could not update the page.";
        }
    } else {
        $message = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Page</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        form { max-width: 600px; margin: 0 auto; }
        label { display: block; margin-bottom: 8px; font-weight: bold; }
        input, textarea { width: 100%; padding: 10px; margin-bottom: 16px; border: 1px solid #ccc; border-radius: 4px; }
        textarea { height: 200px; }
        button { padding: 10px 20px; background-color: #007bff; color: white; border: none; cursor: pointer; }
        button:hover { background-color: #0056b3; }
        .message { margin-bottom: 20px; color: red; font-weight: bold; }
    </style>
</head>
<body>
    <h1>Edit Page</h1>

    <?php if ($message): ?>
        <p class="message"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <form method="post">
        <label for="title">Title</label>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($page['title']); ?>" required>

        <label for="content">Content</label>
        <textarea id="content" name="content" required><?php echo htmlspecialchars($page['content']); ?></textarea>

        <button type="submit">Update Page</button>
    </form>
    <br>
    <a href="index.php">Back to Home</a>
</body>
</html>
