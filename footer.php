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
    footer {
        background-color: #333;
        color: #fff;
        text-align: center;
        padding: 10px 0;
        margin-top: 20px;
    }
    .footer-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }
    footer ul {
        list-style: none;
        padding: 0;
        margin: 10px 0 0;
        display: flex;
        justify-content: center;
        gap: 15px;
    }
    footer ul li {
        display: inline;
    }
    footer ul li a {
        color: #fff;
        text-decoration: none;
    }
    footer ul li a:hover {
        text-decoration: underline;
    }
</style>
