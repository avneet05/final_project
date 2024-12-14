<?php
// Start session and include necessary files
session_start();
include 'db.php'; // Database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

// Fetch some data to display on the homepage if needed
$query = "SELECT title, created_at FROM pages ORDER BY created_at DESC LIMIT 5";
$result = $pdo->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitLife Hub - CMS Dashboard</title>
    <style>
        /* General Reset */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }

        /* Container Styling */
        .container {
            width: 100%;
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        /* Headings */
        h1 {
            font-size: 2rem;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        h2 {
            font-size: 1.5rem;
            color: #34495e;
            margin-top: 30px;
        }

        /* Navigation Menu */
        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        nav ul li {
            display: inline;
        }

        nav ul li a {
            text-decoration: none;
            color: white;
            background-color: #3498db;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
            font-size: 1rem;
        }

        nav ul li a:hover {
            background-color: #2980b9;
        }

        /* Recent Pages Section */
        .recent_pages {
            margin-top: 20px;
            text-align: left;
        }

        .recent_pages ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .recent_pages ul li {
            padding: 10px;
            margin-bottom: 10px;
            background-color: #ecf0f1;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .recent_pages ul li:hover {
            background-color: #dcdde1;
        }

        /* Footer */
        footer {
            margin-top: 20px;
            text-align: center;
            font-size: 0.9rem;
            color: #7f8c8d;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to FitLife Hub CMS</h1>

        <nav>
            <ul>
                <?php if ($_SESSION['role'] === 'admin'): ?>
                    <li><a href="create.php">Create New Page</a></li>
                    <li><a href="user_logins.php">User Logins</a></li>
                <?php endif; ?>
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
                        echo "<li>" . htmlspecialchars($row['title']) . " - Created on: " . htmlspecialchars($row['created_at']) . "</li>";
                    }
                } else {
                    echo "<li>No pages available.</li>";
                }
                ?>
            </ul>
        </div>
    </div>
</body>
</html>

<?php include 'footer.php'; // Footer (optional) ?>
