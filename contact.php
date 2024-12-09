<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - FitLife Hub</title>
    <style>
        /* General Reset */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            color: #333;
        }

        /* Page Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Header Styling */
        header {
            text-align: center;
            padding: 20px 0;
            background-color: #3498db;
            color: #fff;
            margin-bottom: 30px;
        }

        header h1 {
            margin: 0;
            font-size: 2.5rem;
        }

        /* Section Styling */
        section {
            margin-bottom: 40px;
        }

        section h2 {
            font-size: 2rem;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        section p {
            font-size: 1.1rem;
            line-height: 1.6;
            color: #555;
        }

        /* Contact Information */
        .contact-info {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .contact-info ul {
            list-style-type: none;
            padding: 0;
        }

        .contact-info ul li {
            font-size: 1rem;
            margin: 10px 0;
        }

        .contact-info ul li strong {
            color: #3498db;
        }

        /* Contact Form Styling */
        .contact-form {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .contact-form form {
            display: flex;
            flex-direction: column;
        }

        .contact-form form label {
            font-weight: bold;
            margin-bottom: 5px;
            color: #2c3e50;
        }

        .contact-form form input,
        .contact-form form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #bdc3c7;
            border-radius: 5px;
            font-size: 1rem;
            background-color: #f9f9f9;
            transition: border-color 0.3s ease;
        }

        .contact-form form input:focus,
        .contact-form form textarea:focus {
            border-color: #3498db;
            outline: none;
        }

        .contact-form form button {
            padding: 12px;
            background-color: #3498db;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .contact-form form button:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <header>
        <h1>Contact Us - FitLife Hub</h1>
    </header>
    <div class="container">
        <!-- Contact Information Section -->
        <section>
            <h2>Our Contact Information</h2>
            <div class="contact-info">
                <ul>
                    <li><strong>Email:</strong> info@fitlifehub.com</li>
                    <li><strong>Phone:</strong> +1 (555) 123-4567</li>
                    <li><strong>Address:</strong> 456 FitLife Avenue, Winnipeg, MB, Canada</li>
                    <li><strong>Working Hours:</strong> Monday to Friday, 8:00 AM - 6:00 PM</li>
                </ul>
            </div>
        </section>

        <!-- Contact Form Section -->
        <section>
            <h2>Get in Touch</h2>
            <div class="contact-form">
                <form action="submit_contact.php" method="POST">
                    <label for="name">Full Name:</label>
                    <input type="text" id="name" name="name" required>

                    <label for="email">Email Address:</label>
                    <input type="email" id="email" name="email" required>

                    <label for="message">Message:</label>
                    <textarea id="message" name="message" rows="5" required></textarea>

                    <button type="submit">Submit</button>
                </form>
            </div>
        </section>
    </div>
</body>
</html>
