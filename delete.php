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

    // Prepare the delete query
    $query = "DELETE FROM pages WHERE page_id = :page_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':page_id', $pageId, PDO::PARAM_INT);
    $stmt->execute();
}

// Redirect to view pages after deletion
header('Location: view_pages.php');
exit();
