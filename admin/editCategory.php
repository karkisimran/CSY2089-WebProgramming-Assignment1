<?php
session_start();

// Check if user is an admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: login.php');
    exit();
}

require_once 'db.php';

$error = '';
$category = null;

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_id = $_POST['category_id'];
    $category_name = trim($_POST['category_name']);

    if (empty($category_name)) {
        $error = "Category name cannot be empty.";
    } else {
        $stmt = $Connection->prepare("UPDATE category SET category_name = :name WHERE category_id = :id");
        $stmt->execute([
            ':name' => $category_name,
            ':id' => $category_id
        ]);
        header("Location: adminCategories.php");
        exit();
    }
}

// Fetch the category to edit
if (isset($_GET['id'])) {
    $stmt = $Connection->prepare("SELECT * FROM category WHERE category_id = :id");
    $stmt->execute([':id' => $_GET['id']]);
    $category = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$category) {
        $error = "Category not found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Category</title>
    <style>
        body {
            margin: 0;
            font-family: "Segoe UI", sans-serif;
            background: linear-gradient(to right, rgb(51, 118, 252), rgb(183, 177, 250));
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .form-box {
            background: #e7f0ff;
            padding: 30px 25px;
            border-radius: 12px;
            box-shadow: 0 20px 25px rgba(0, 0, 0, 0.1);
            width: 340px;
            max-width: 100%;
            margin: 20px;
        }

        .form-box h2 {
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }

        .form-box label {
            display: block;
            margin: 10px 0 5px;
            font-size: 14px;
            font-weight: 600;
        }

        .form-box input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        .form-box button {
            width: 100%;
            padding: 10px;
            background-color: rgb(40, 157, 235);
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
        }

        .form-box button:hover {
            background-color: rgb(0, 61, 217);
        }

        .form-box .error {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }

        .form-box a {
            color: #007bff;
            text-decoration: none;
            font-size: 14px;
            display: block;
            text-align: center;
            margin-top: 15px;
        }

        .form-box a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="form-box">
    <h2>Edit Category</h2>

    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php elseif ($category): ?>
        <form method="POST" action="">
            <input type="hidden" name="category_id" value="<?= htmlspecialchars($category['category_id']) ?>">
            <label for="category_name">Category Name:</label>
            <input type="text" name="category_name" id="category_name" value="<?= htmlspecialchars($category['category_name']) ?>" required>
            <button type="submit">Save Changes</button>
        </form>
        <a href="adminCategories.php">← Back to Categories</a>
    <?php else: ?>
        <p class="error">No category selected.</p>
        <a href="adminCategories.php">← Back to Categories</a>
    <?php endif; ?>
</div>

</body>
</html>
