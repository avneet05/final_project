<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo "Access Denied: Only admins can delete pages.";
    exit();
}

require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['page_id'])) {
    $page_id = $_GET['page_id'];

    $stmt = $pdo->prepare("DELETE FROM pages WHERE page_id = :page_id");
    $stmt->bindParam(':page_id', $page_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header("Location: view_pages.php");
        exit();
    } else {
        echo "Error: Could not delete the page.";
    }
}
?>
