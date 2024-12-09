<?php
// Start session
session_start();

// Include database connection if needed
include 'db.php'; // Make sure 'db.php' contains the PDO connection setup

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get and sanitize form inputs
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $message = trim($_POST['message']);

    // Validate inputs
    if (!empty($name) && !empty($email) && !empty($message)) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            try {
                // OPTIONAL: Save the message in the database
                $query = "INSERT INTO contact_messages (name, email, message, submitted_at) VALUES (:name, :email, :message, NOW())";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                $stmt->bindParam(':message', $message, PDO::PARAM_STR);
                $stmt->execute();

                // Display success message
                $_SESSION['success'] = "Thank you, $name! Your message has been successfully submitted.";
                header("Location: contact.php");
                exit();
            } catch (PDOException $e) {
                // Handle database errors
                $_SESSION['error'] = "Error saving your message. Please try again later.";
                header("Location: contact.php");
                exit();
            }
        } else {
            // Invalid email
            $_SESSION['error'] = "Please provide a valid email address.";
            header("Location: contact.php");
            exit();
        }
    } else {
        // Missing required fields
        $_SESSION['error'] = "All fields are required. Please fill out the form completely.";
        header("Location: contact.php");
        exit();
    }
} else {
    // If the user tries to access this page directly
    $_SESSION['error'] = "Invalid request method.";
    header("Location: contact.php");
    exit();
}
