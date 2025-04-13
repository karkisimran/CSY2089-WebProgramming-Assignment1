<?php
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: login.php');
    exit();
}

require_once 'db.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_name = trim($_POST['category_name']);

    if (empty($category_name)) {
        $error = 'Category name cannot be empty.';
    } else {
        $stmt = $Connection->prepare("INSERT INTO category (category_name) VALUES (:category_name)");
        $stmt->execute([':category_name' => $category_name]);
        header('Location: adminCategories.php');
        exit();
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Category</title>
    <style>
        body {
            margin: 0;
            font-family: "Segoe UI", sans-serif;
            background:linear-gradient(to right, rgb(70, 128, 243), rgb(183, 177, 250));
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .form-box {
            background: #f0f4ff;
            padding: 30px 25px;
            border-radius: 12px;
            box-shadow: 0 20px 25px rgba(0, 0, 0, 0.1);
            width: 350px;
        }

        .form-box h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .form-box label {
            font-weight: 600;
            display: block;
            margin-bottom: 6px;
        }

        .form-box input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        .form-box button {
            width: 100%;
            padding: 10px;
            background-color: #289deb;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
        }

        .form-box button:hover {
            background-color: #003dd9;
        }

        .form-box p {
            margin-top: 15px;
            text-align: center;
        }

        .form-box .error {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }

        .form-box a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }

        .form-box a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>



<div class="form-box">
    <h2>Add New Category</h2>
    <?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>
    <form method="POST">
        <label for="category_name">Category Name:</label>
        <input type="text" name="category_name" id="category_name" required>
        <button type="submit">Add Category</button>
    </form>
    <p><a href="adminCategories.php">← Back to Categories</a></p>
</div>

</body>
</html>
