<?php
session_start();
include 'db.php'; // Include the database connection file

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        try {
            // Query to fetch user by username
            $query = "SELECT * FROM users WHERE username = :username LIMIT 1";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verify the password
            if ($user && password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                // Redirect to dashboard or homepage
                header("Location: index.php");
                exit();
            } else {
                $error = "Invalid username or password.";
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    } else {
        $error = "Please enter both username and password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        /* General Reset */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9; /* Light gray background */
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
        }

        /* Heading at the top */
        h1 {
            font-size: 2.5rem;
            color: #3498db;
            margin-top: 20px;
            text-align: center;
        }

        /* Main layout container */
        .main-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 20px;
            width: 100%;
            max-width: 1200px;
        }

        /* Side images */
        .side-image {
            flex: 1;
            background: url('workout.jpg') no-repeat left center;
            max-width: 600px;
            height: 200px;
        }

        /* Login container */
        .container {
            flex: 2;
            max-width: 400px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        /* Headings inside the form */
        h2 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-size: 1.8rem;
        }

        /* Error Message */
        .error {
            margin-bottom: 20px;
            color: #e74c3c;
            background-color: #fdecea;
            padding: 10px;
            border-radius: 5px;
            font-size: 0.9rem;
        }

        /* Form Styling */
        form {
            margin: 0 auto;
            width: 100%;
            text-align: left;
        }

        form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #2c3e50;
        }

        form input[type="text"],
        form input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #bdc3c7;
            border-radius: 5px;
            font-size: 1rem;
            background-color: #f9f9f9;
            transition: border-color 0.3s ease;
        }

        form input[type="text"]:focus,
        form input[type="password"]:focus {
            border-color: #3498db;
            outline: none;
        }

        /* Button Styling */
        form button {
            width: 100%;
            padding: 12px;
            background-color: #3498db;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form button:hover {
            background-color: #2980b9;
        }

        /* Link Styling */
        form a {
            color: #3498db;
            text-decoration: none;
            font-size: 0.9rem;
            display: block;
            margin-top: 10px;
        }

        form a:hover {
            text-decoration: underline;
        }

        /* Footer for extra information */
        .container p {
            font-size: 0.9rem;
            margin-top: 15px;
            color: #7f8c8d;
        }
    </style>
</head>
<body>
    <h1>Welcome to FitLife Hub</h1> <!-- Heading at the top -->
    <div class="main-container">
        <div class="side-image"></div> <!-- Left workout image -->
        <div class="container">
            <h2>Login</h2>
            <?php if (!empty($error)) : ?>
                <div class="error">
                    <p><?php echo $error; ?></p>
                </div>
            <?php endif; ?>
            <form action="login.php" method="POST">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" required>

                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>

                <button type="submit">Login</button>
            </form>
            <p>Don't have an account? <a href="register.php">Register here</a>.</p>
        </div>
        <div class="side-image"></div> <!-- Right workout image -->
    </div>
</body>
</html>
