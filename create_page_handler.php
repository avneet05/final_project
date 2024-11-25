<?php
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['role'] === 'admin') {
    $title = $_POST['title'];
    $content = $_POST['content'];

    $stmt = $pdo->prepare("INSERT INTO pages (title, content) VALUES (?, ?)");
    $stmt->execute([$title, $content]);

    echo "Page created successfully.";
} else {
    echo "Access Denied";
}
?>
