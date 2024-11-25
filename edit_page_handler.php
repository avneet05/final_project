<?php
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['role'] === 'admin') {
    $page_id = $_POST['page_id'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    $stmt = $pdo->prepare("UPDATE pages SET title = ?, content = ? WHERE page_id = ?");
    $stmt->execute([$title, $content, $page_id]);

    echo "Page updated successfully.";
} else {
    echo "Access Denied";
}
?>
