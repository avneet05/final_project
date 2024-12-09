<footer>
    <div class="footer-container">
        <p>&copy; <?php echo date("Y"); ?> FitLife Hub. All rights reserved.</p>
        <nav>
            <ul>
                <li><a href="about.php">About Us</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="terms.php">Terms of Service</a></li>
                <li><a href="privacy.php">Privacy Policy</a></li>
            </ul>
        </nav>
    </div>
</footer>

<style>
    /* General body and container styling for sticky footer */
    body {
        margin: 0;
        display: flex;
        flex-direction: column;
        min-height: 100vh; /* Ensures the body takes full height of the viewport */
    }

    .main-content {
        flex: 1; /* Ensures the content area expands to fill space */
    }

    footer {
        background-color: #007bff; 
        color: #ffffff; 
        text-align: center;
        padding: 20px 0;
        font-weight: normal;
        width: 100%;
    }

    .footer-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    footer p {
        font-size: 14px;
        margin: 0;
    }

    footer ul {
        list-style: none;
        padding: 0;
        margin: 10px 0 0;
        display: flex;
        justify-content: center;
        gap: 20px;
    }

    footer ul li {
        display: inline;
    }

    footer ul li a {
        color: #ffffff; /* Matching link color */
        text-decoration: none;
        font-size: 16px;
    }

    footer ul li a:hover {
        text-decoration: underline;
    }

    /* Responsive Design: Footer Links on Smaller Screens */
    @media (max-width: 768px) {
        footer ul {
            flex-direction: column;
            align-items: center;
        }
        footer ul li {
            margin-bottom: 10px;
        }
    }
</style>
