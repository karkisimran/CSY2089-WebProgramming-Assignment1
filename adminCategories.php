<?php
require('db.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Restrict access to admin users only
$isAdmin = $_SESSION['is_admin'] ?? 0;
$loggedIn = $_SESSION['logged-in'] ?? false;

if (!$loggedIn || $isAdmin != 1) {
    header("Location: login.php");
    exit();
}

// Fetch all categories from database
$stmt = $Connection->query("SELECT * FROM category");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Categories</title>
    <link rel="stylesheet" href="carbuy.css">
   
    <style>
        .admin-table {
            margin: 40px auto;
            width: 80%;
            border-collapse: collapse;
        }
        .admin-table th, .admin-table td {
            padding: 12px 16px;
            border: 1px solid #ccc;
        }
        .admin-table th {
            background-color: #f4f4f4;
        }
        .action-links a {
            margin-right: 10px;
            color: white;
            padding: 6px 10px;
            border-radius: 4px;
            text-decoration: none;
        }
        .edit-btn {
            background-color: #28a745;
        }
        .delete-btn {
            background-color: #dc3545;
        }
        .add-btn {
            display: inline-block;
            margin: 20px auto;
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            font-weight: bold;
            text-decoration: none;
            border-radius: 5px;
        }
        .add-category-btn {
            display: inline-block;
            padding: 8px 16px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        .add-category-btn:hover {
            background-color: #0056b3;
        }

    </style>
</head>
<body>
<?php include('header.php'); ?>

<main>
    <h1 style="text-align: center;">Manage Categories</h1>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Category Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $cat): ?>
                <tr>
                    <td><?= htmlspecialchars($cat['category_name']) ?></td>
                    <td class="action-links">
                        <a class="edit-btn" href="editCategory.php?category_id=<?= $cat['category_id'] ?>">Edit</a>
                        <a class="delete-btn" href="deleteCategory.php?category_id=<?= $cat['category_id'] ?>" onclick="return confirm('Are you sure you want to delete this category?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div style="text-align: center;">
        <a class="add-btn" href="addCategory.php">+ Add New Category</a>
    </div>
</main>
</body>
</html>
