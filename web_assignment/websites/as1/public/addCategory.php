<?php
session_start();

// Check admin access
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit();
}

// Include database connection
require_once 'db.php';

$feedback = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categoryName = trim($_POST['category_name'] ?? '');

    if (!empty($categoryName)) {
        try {
            $stmt = $Connection->prepare("INSERT INTO category (category_name) VALUES (:name)");
            $stmt->execute(['name' => $categoryName]);
            header("Location: adminCategories.php");
            exit();
        } catch (PDOException $e) {
            $feedback = "Error saving category. Please try again.";
        }
    } else {
        $feedback = "Category name cannot be empty.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Category</title>
    <link rel="stylesheet" href="styles.css"> 
    <style>
        .add-category-container {
            max-width: 500px;
            margin: 30px auto;
            padding: 20px;
            background: #f3f3f3;
            border-radius: 8px;
            box-shadow: 0 0 10px #ccc;
        }
        .add-category-container h2 {
            margin-bottom: 15px;
            text-align: center;
        }
        .add-category-container input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .add-category-container button {
            width: 100%;
            padding: 10px;
            background: #4285f4;
            color: white;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
        }
        .add-category-container button:hover {
            background: #3367d6;
        }
        .feedback {
            text-align: center;
            color: red;
            margin-bottom: 10px;
        }
        .back-link {
            display: block;
            margin-top: 15px;
            text-align: center;
            color: #333;
            text-decoration: none;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="add-category-container">
    <h2>Add New Category</h2>

    <?php if ($feedback): ?>
        <div class="feedback"><?= htmlspecialchars($feedback) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <input type="text" name="category_name" placeholder="Enter category name" required>
        <button type="submit">Add Category</button>
    </form>

    <a class="back-link" href="adminCategories.php">⟵ Back to Categories</a>
</div>

</body>
</html>
