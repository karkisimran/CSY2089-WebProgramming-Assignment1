<?php
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: login.php');
    exit();
}

require_once 'db.php';

// Fetch all categories
$stmt = $Connection->query("SELECT * FROM category");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Categories</title>
    <style>
        body {
            font-family: "Segoe UI", sans-serif;
            background: white;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            padding: 20px;
            color: #333;
        }

        .add-btn {
            display: block;
            width: fit-content;
            margin: 0 auto 20px auto;
            padding: 10px 15px;
            background-color: rgb(40, 157, 235);
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
        }

        .add-btn:hover {
            background-color: rgb(0, 61, 217);
        }

        table {
            width: 80%;
            margin: 0 auto;
            border-collapse: collapse;
            background-color: #f1f5ff;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            padding: 15px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #d6e0ff;
            font-weight: bold;
        }

        .edit-btn, .delete-btn {
            padding: 6px 10px;
            text-decoration: none;
            color: white;
            border-radius: 5px;
            font-weight: bold;
        }

        .edit-btn {
            background-color: #28a745;
        }

        .edit-btn:hover {
            background-color: #218838;
        }

        .delete-btn {
            background-color: #dc3545;
        }
        .delete-btn:hover {
            background-color: #c82333;
        }
        
    </style>
</head>
<body>
<?php include 'includes/header.php'; ?>

<h2>Manage Categories</h2>
<a href="addCategory.php" class="add-btn">+ Add New Category</a>

<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($categories as $row): ?>
    <tr>
        <td><?= htmlspecialchars($row['category_id']) ?></td>
        <td><?= htmlspecialchars($row['category_name']) ?></td>
        <td>
            <a href="editCategory.php?id=<?= htmlspecialchars($row['category_id']) ?>" class="edit-btn">Edit</a>
            <a href="deleteCategory.php?id=<?= htmlspecialchars($row['category_id']) ?>" class="delete-btn" onclick="return confirm('Are you sure?')">Delete</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
</body>
</html>
<?php include 'includes/footer.php'; ?>
