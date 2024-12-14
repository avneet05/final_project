<?php
session_start();
require 'db.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if page ID is provided
if (isset($_GET['id'])) {
    $pageId = $_GET['id'];

    // Fetch the page data
    $query = "SELECT * FROM pages WHERE page_id = :page_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':page_id', $pageId, PDO::PARAM_INT);
    $stmt->execute();
    $page = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$page) {
        // Redirect to the view pages if page not found
        header('Location: view_pages.php');
        exit();
    }

    // Handle the form submission for editing the page
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = $_POST['title'];
        $content = $_POST['content'];

        // Update the page in the database
        $updateQuery = "UPDATE pages SET title = :title, content = :content, updated_at = NOW() WHERE page_id = :page_id";
        $updateStmt = $pdo->prepare($updateQuery);
        $updateStmt->bindParam(':title', $title, PDO::PARAM_STR);
        $updateStmt->bindParam(':content', $content, PDO::PARAM_STR);
        $updateStmt->bindParam(':page_id', $pageId, PDO::PARAM_INT);
        $updateStmt->execute();

        // Redirect back to view pages after editing
        header('Location: view_pages.php');
        exit();
    }
} else {
    // Redirect to view pages if no ID provided
    header('Location: view_pages.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Page</title>
</head>
<body>
    <h1>Edit Page</h1>
    <form method="POST">
        <label for="title">Title:</label><br>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($page['title']); ?>"><br><br>
        <label for="content">Content:</label><br>
        <textarea id="content" name="content"><?php echo htmlspecialchars($page['content']); ?></textarea><br><br>
        <button type="submit">Save Changes</button>
    </form>
</body>
</html>
