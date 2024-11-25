<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    echo "Access Denied";
    exit;
}

include 'db.php';
$page_id = $_GET['page_id'];
$stmt = $pdo->prepare("SELECT * FROM pages WHERE page_id = ?");
$stmt->execute([$page_id]);
$page = $stmt->fetch();
?>
<form method="POST" action="edit_page_handler.php">
    <input type="hidden" name="page_id" value="<?= $page['page_id'] ?>">
    <label>Title:</label>
    <input type="text" name="title" value="<?= htmlspecialchars($page['title']) ?>" required><br>
    <label>Content:</label>
    <textarea name="content" required><?= htmlspecialchars($page['content']) ?></textarea><br>
    <button type="submit">Update Page</button>
</form>
