<?php
session_start();
require_once 'db.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch categories
$stmt = $Connection->query("SELECT * FROM category");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $end_date = $_POST['end_date'];
    $category_id = $_POST['category'];

    $stmt = $Connection->prepare("INSERT INTO auction (title, description, endDate, userId, categoryId) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$title, $description, $end_date, $user_id, $category_id]);

    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Post Auction</title>
    <link rel="stylesheet" href="style.css">
    <!-- <style>
        .post-auction-form {
            width: 80%;
            max-width: 500px;
            margin: 40px auto;
            padding: 20px;
            background-color: #f4f4f4;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        } -->

        /* .post-auction-form input,
        .post-auction-form textarea,
        .post-auction-form select,
        .post-auction-form button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .post-auction-form button {
            background-color: #4CAF50;
            color: white;
            border: none;
            font-size: 16px;
        }

        .post-auction-form button:hover {
            background-color: #45a049;
        }

        h2 {
            text-align: center;
            margin-top: 30px;
            font-weight: 500; */
        }
    <!-- </style>s -->
</head>
<body>

<?php include 'header.php'; ?>

<h2>Post Your Auction</h2>

<div class="post-auction-form">
    <form action="postAuction.php" method="POST">
        <label for="title">Auction Title</label>
        <input type="text" id="title" name="title" required>

        <label for="description">Description</label>
        <textarea id="description" name="description" required></textarea>

        <label for="end_date">End Date</label>
        <input type="datetime-local" id="end_date" name="end_date" required>

        <label for="category">Category</label>
        <select id="category" name="category" required>
            <option value="">Select a Category</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?= $category['category_id']; ?>"><?= htmlspecialchars($category['category_name']); ?></option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Post Auction</button>
    </form>
</div>

</body>
</html>
