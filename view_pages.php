<?php
session_start();
require 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Assume user role is stored in session as 'role' (it should be set during login)
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
$userId = $_SESSION['user_id']; // Get the logged-in user ID
$username = $_SESSION['username']; // Get the logged-in user's name

// CAPTCHA generation (if not already generated)
if (!isset($_SESSION['captcha'])) {
    $_SESSION['captcha'] = rand(1000, 9999); // Generate a 4-digit random CAPTCHA
}

// Generate CAPTCHA image
if (isset($_GET['captcha']) && $_GET['captcha'] == 'true') {
    header('Content-Type: image/png');
    $captcha = $_SESSION['captcha'];
    $image = imagecreatetruecolor(120, 40);
    $bgColor = imagecolorallocate($image, 255, 255, 255);
    $textColor = imagecolorallocate($image, 0, 0, 0);
    imagefill($image, 0, 0, $bgColor);
    imagestring($image, 5, 30, 10, $captcha, $textColor);
    imagepng($image);
    imagedestroy($image);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle comment submission
    $captcha = $_POST['captcha'] ?? '';
    $comment = $_POST['comment'] ?? '';
    $pageId = $_POST['page_id'] ?? '';

    if ($captcha !== $_SESSION['captcha']) {
        $captchaError = "Incorrect CAPTCHA. Please try again.";
    } elseif (!empty($comment)) {
        // Insert the comment into the database
        $stmt = $pdo->prepare("INSERT INTO comments (page_id, user_id, comment, created_at) VALUES (:page_id, :user_id, :comment, NOW())");
        $stmt->bindParam(':page_id', $pageId, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt->execute();
        $successMessage = "Your comment has been submitted successfully.";
    }
}

// Fetch sorting and search inputs
$sort = $_GET['sort'] ?? 'created_at';
$allowedSorts = ['title', 'created_at', 'updated_at'];
$order = in_array($sort, $allowedSorts) ? $sort : 'created_at';

$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';

// Fetch categories dynamically from the database
$stmt = $pdo->query("SELECT category_id, category_name FROM categories");
$categories = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

// Build query dynamically to filter by search and category
$query = "
    SELECT p.*, c.category_name 
    FROM pages p
    LEFT JOIN categories c ON p.category_id = c.category_id
    WHERE p.title LIKE :search
";

// Add category filter if selected
if ($category) {
    $query .= " AND p.category_id = :category";
}

$query .= " ORDER BY $order ASC";

$stmt = $pdo->prepare($query);
$searchTerm = '%' . $search . '%';
$stmt->bindParam(':search', $searchTerm, PDO::PARAM_STR);

// Bind the category if selected
if ($category) {
    $stmt->bindParam(':category', $category, PDO::PARAM_INT);
}

$stmt->execute();
$pages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch comments for each page
$pageComments = [];
foreach ($pages as $page) {
    $stmt = $pdo->prepare("SELECT c.comment, c.created_at, u.username FROM comments c LEFT JOIN users u ON c.user_id = u.user_id WHERE c.page_id = :page_id");
    $stmt->bindParam(':page_id', $page['page_id'], PDO::PARAM_INT);
    $stmt->execute();
    $pageComments[$page['page_id']] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Pages</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        header {
            margin-bottom: 20px;
        }
        header a {
            margin-right: 15px;
            text-decoration: none;
            color: #007BFF;
        }
        header a:hover {
            text-decoration: underline;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            padding: 8px 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        input, select, button {
            padding: 8px;
            margin-right: 10px;
        }
        .actions {
            text-align: right;
        }
        .actions a {
            margin-left: 10px;
            text-decoration: none;
            color: #007BFF;
        }
        .actions a:hover {
            text-decoration: underline;
        }
        .comment-form {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }
        .comment-list {
            margin-top: 20px;
        }
        .comment-item {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .comment-right {
            float: right;
            width: 40%;
            padding: 20px;
            border-left: 2px solid #ddd;
        }
        .captcha-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
        }
        .captcha-image {
            display: inline-block;
            margin-top: 10px;
        }
        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            overflow: auto;
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <header>
        <a href="index.php">Home</a>
        <a href="logout.php">Logout</a>
    </header>

    <h1>View Pages</h1>

    <!-- Search, Sort, and Category Filter Form -->
    <form method="GET" style="margin-bottom: 20px;">
        <input type="text" name="search" placeholder="Search by title..." value="<?php echo htmlspecialchars($search); ?>">
        <select name="sort" onchange="this.form.submit()">
            <option value="created_at" <?php echo $sort === 'created_at' ? 'selected' : ''; ?>>Created Date</option>
            <option value="updated_at" <?php echo $sort === 'updated_at' ? 'selected' : ''; ?>>Updated Date</option>
            <option value="title" <?php echo $sort === 'title' ? 'selected' : ''; ?>>Title</option>
        </select>
        <select name="category" onchange="this.form.submit()">
            <option value="">All Categories</option>
            <?php foreach ($categories as $id => $name): ?>
                <option value="<?php echo $id; ?>" <?php echo $category == $id ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($name); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Search</button>
    </form>

    <!-- Pages Table -->
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Category</th>
                <th>Content</th>
                <th>Created At</th>
                <th>Updated At</th>
                <?php if ($isAdmin): ?>
                    <th>Actions</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($pages)): ?>
                <?php foreach ($pages as $page): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($page['title']); ?></td>
                        <td><?php echo htmlspecialchars($page['category_name']); ?></td>
                        <td><?php echo htmlspecialchars(substr($page['content'], 0, 50)) . '...'; ?></td>
                        <td><?php echo htmlspecialchars($page['created_at']); ?></td>
                        <td><?php echo htmlspecialchars($page['updated_at']); ?></td>
                        <?php if ($isAdmin): ?>
                            <td class="actions">
                                <a href="edit_pages.php?id=<?php echo $page['page_id']; ?>">Edit</a> |
                                <a href="delete.php?id=<?php echo $page['page_id']; ?>" onclick="return confirm('Are you sure you want to delete this page?');">Delete</a>
                            </td>
                        <?php endif; ?>
                    </tr>

                    <!-- Display Comments for the Page -->
                    <tr>
                        <td colspan="<?php echo $isAdmin ? '6' : '5'; ?>">
                            <div class="comment-right">
                                <h3>Comments</h3>
                                <?php if (isset($pageComments[$page['page_id']])): ?>
                                    <div class="comment-list">
                                        <?php foreach ($pageComments[$page['page_id']] as $comment): ?>
                                            <div class="comment-item">
                                                <strong><?php echo htmlspecialchars($comment['username']); ?>:</strong>
                                                <p><?php echo htmlspecialchars($comment['comment']); ?></p>
                                                <small>Posted on <?php echo htmlspecialchars($comment['created_at']); ?></small>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>

                                <?php if (!$isAdmin): ?>
                                    <!-- Comment Submission Form for Users -->
                                    <div class="comment-form">
                                        <form id="commentForm" method="POST">
                                            <input type="hidden" name="page_id" value="<?php echo $page['page_id']; ?>">
                                            <textarea name="comment" placeholder="Write your comment..." required></textarea><br>
                                            
                                            <button type="button" onclick="showCaptchaModal()">Submit Comment</button>
                                        </form>
                                        <?php if (!empty($captchaError)): ?>
                                            <p style="color: red;"><?php echo $captchaError; ?></p>
                                        <?php endif; ?>
                                        <?php if (!empty($successMessage)): ?>
                                            <p style="color: green;"><?php echo $successMessage; ?></p>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="<?php echo $isAdmin ? '6' : '5'; ?>">No pages found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- CAPTCHA Modal -->
    <div id="captchaModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h3>Please enter the CAPTCHA to verify you're a human:</h3>
            <img src="view_pages.php?captcha=true" alt="CAPTCHA" class="captcha-image"><br>
            <input type="text" id="captchaInput" placeholder="Enter CAPTCHA" required>
            <button onclick="submitComment()">Submit Comment</button>
        </div>
    </div>

    <script>
        function showCaptchaModal() {
            // Show the CAPTCHA modal when the user clicks the submit button
            document.getElementById('captchaModal').style.display = 'block';
        }

        function closeModal() {
            // Close the CAPTCHA modal
            document.getElementById('captchaModal').style.display = 'none';
        }

        function submitComment() {
            const captchaInput = document.getElementById('captchaInput').value;
            const correctCaptcha = "<?php echo $_SESSION['captcha']; ?>";
            
            // Check if CAPTCHA is correct
            if (captchaInput === correctCaptcha) {
                document.getElementById('commentForm').submit();
            } else {
                alert("Incorrect CAPTCHA. Please try again.");
            }
        }
    </script>
</body>
</html>
