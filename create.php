<?php
// Start a session if one is not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Restrict access to admins only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo "Access Denied: Only admins can create pages.";
    exit();
}

require 'db.php';

$message = ''; // To display messages to the admin

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if (!empty($title) && !empty($content)) {
        try {
            // Insert the new page into the database
            $query = "INSERT INTO pages (title, content) VALUES (:title, :content)";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':title', $title, PDO::PARAM_STR);
            $stmt->bindParam(':content', $content, PDO::PARAM_STR);

            if ($stmt->execute()) {
                // Redirect to home page after successful creation
                header("Location: index.php");
                exit();
            } else {
                $message = "Error: Could not create the page.";
            }
        } catch (PDOException $e) {
            $message = "Database error: " . $e->getMessage();
        }
    } else {
        $message = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f9f9f9;
        }
        form {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        textarea {
            height: 200px;
        }
        button {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
            border-radius: 4px;
        }
        button:hover {
            background-color: #218838;
        }
        .message {
            text-align: center;
            margin-bottom: 20px;
            color: #d9534f; /* Red for errors */
            font-weight: bold;
        }
        .message.success {
            color: #28a745; /* Green for success */
        }
    </style>
</head>
<body>
    <h1>Create a New Page</h1>

    <?php if (!empty($message)) : ?>
        <p class="message <?php echo (strpos($message, 'successfully') !== false) ? 'success' : ''; ?>">
            <?php echo htmlspecialchars($message); ?>
        </p>
    <?php endif; ?>

    <form method="post">
        <label for="title">Title</label>
        <input type="text" id="title" name="title" required>

        <label for="content">Content</label>
        <textarea id="content" name="content" required></textarea>

        <button type="submit">Create Page</button>
    </form>
</body>
</html>
