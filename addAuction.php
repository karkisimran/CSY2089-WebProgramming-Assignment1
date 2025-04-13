<?php
session_start();
require 'db.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 0) {
    header('Location: login.php');
    exit();
}

// Fetch categories for dropdown
$categories = $Connection->query("SELECT * FROM category")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $categoryId = $_POST['categoryId'];
    $endDate = $_POST['endDate'];
    $userId = $_SESSION['user_id'];
    $imageName = null;

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageTmpPath = $_FILES['image']['tmp_name'];
        $imageName = basename($_FILES['image']['name']);
        $targetDir = 'images/auction/';
        $targetFilePath = $targetDir . $imageName;

        // Save uploaded file
        move_uploaded_file($imageTmpPath, $targetFilePath);
    }

    // Insert auction
    $stmt = $Connection->prepare("INSERT INTO auction (title, description, categoryId, endDate, userId, image, created_at) 
        VALUES (:title, :description, :categoryId, :endDate, :userId, :image, NOW())");

    $stmt->execute([
        'title' => $title,
        'description' => $description,
        'categoryId' => $categoryId,
        'endDate' => $endDate,
        'userId' => $userId,
        'image' => $imageName
    ]);

    header('Location: listings.php'); // or index.php if you prefer
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Auction</title>
    <link rel="stylesheet" href="carbuy.css">
</head>
<body>

<?php include 'includes/header.php'; ?>

<div class="form-container">
    <h2>Add a New Auction</h2>
    <form action="addAuction.php" method="POST" enctype="multipart/form-data">
        <label for="title">Title:</label>
        <input type="text" name="title" required>

        <label for="description">Description:</label>
        <textarea name="description" required></textarea>

        <label for="categoryId">Category:</label>
        <select name="categoryId" required>
            <option value="">Select a category</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['category_id'] ?>"><?= htmlspecialchars($cat['category_name']) ?></option>
            <?php endforeach; ?>
        </select>

        <label for="endDate">End Date:</label>
        <input type="datetime-local" name="endDate" required>

        <label for="image">Upload Car Image:</label>
        <input type="file" name="image" accept="image/*">

        <button type="submit">Create Auction</button>
    </form>
</div>

</body>
</html>
