<?php

$dsn = 'mysql:host=localhost;dbname=web_project;charset=utf8mb4';
$username = 'root';
$password = ''; 

try {
    $pdo = new PDO($dsn, $username, $password);
    echo "Connection successful!";
} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage();
}


?>
