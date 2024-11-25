<?php
include 'db.php';
session_start();

if ($_SESSION['role'] === 'admin') {
    $page_id = $_GET['page_id'];
    $stmt = $pdo->prepare("DELETE FROM pages WHERE page_id = ?");
    $stmt->execute([$page_id]);

    echo "Page deleted successfully.";
} else {
    echo "Access Denied";
}
?>
