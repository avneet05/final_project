register.php

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        /* General Reset */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9; /* Light gray background */
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        /* Container for the register form */
        .container {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        /* Headings */
        h1 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-size: 1.8rem;
        }

        /* Message Styling */
        p {
            color: #333;
            font-size: 0.9rem;
        }

        /* Success/Error Message Styling */
        .message {
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 5px;
            font-size: 0.9rem;
        }

        .message.success {
            color: #27ae60;
            background-color: #eafaf1;
        }

        .message.error {
            color: #e74c3c;
            background-color: #fdecea;
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
    <div class="container">
        <h1>Register</h1>
        <?php if (!empty($message)): ?>
            <div class="message <?php echo $messageType ?? ''; ?>">
                <p><?php echo htmlspecialchars($message); ?></p>
            </div>
        <?php endif; ?>
        <form action="register.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a>.</p>
    </div>
</body>
</html>
