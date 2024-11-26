<?php
// Start session and include necessary files
session_start();
include 'db.php'; // Database connection
//include 'header.php'; // Header (optional, if you have a separate header file)

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

// Fetch some data to display on the homepage if needed
// Example: Fetch list of pages or classes
$query = "SELECT title, created_at FROM pages ORDER BY created_at DESC LIMIT 5";
$result = $pdo->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitLife Hub - CMS Dashboard</title>
    <link rel="stylesheet" href="style.css"> <!-- Link to your CSS file -->
</head>
<body>
    <div class="container">
        <h1>Welcome to FitLife Hub CMS</h1>

        <nav>
            <ul>
                <li><a href="create.php">Create New Page</a></li>
                <li><a href="view_pages.php">View All Pages</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
        <div class="recent_pages">
        <h2>Recent Pages</h2>
        <ul>
            <?php
            // Display recent pages
            if ($result) {
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    echo "<li>{$row['title']} - Created on: {$row['created_at']}</li>";
                }
            } else {
                echo "<li>No pages available.</li>";
            }
            ?>
        </ul></div>
    </div>
</body>
</html>

<?php include 'footer.php'; // Footer (optional) ?>
