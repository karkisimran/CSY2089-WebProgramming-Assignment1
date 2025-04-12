<?php
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: login.php');
    exit();
}

require_once 'db.php';

if (!isset($_GET['id'])) {
    header('Location: adminCategories.php');
    exit();
}

$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM category WHERE id = ?");
$stmt->execute([$id]);
$category = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$category) {
    echo "Category not found.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    if (!empty($name)) {
        $updateStmt = $pdo->prepare("UPDATE category SET name = :name WHERE id = :id");
        $updateStmt->execute(['name' => $name, 'id' => $id]);
        header('Location: adminCategories.php');
        exit();
    } else {
        $error = "Category name is required.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Category</title>
</head>
<body>
<h2>Edit Category</h2>
<form method="post">
    <label>Category Name:</label><br>
    <input type="text" name="name" value="<?= htmlspecialchars($category['name']) ?>" required><br><br>
    <button type="submit">Save Changes</button>
</form>
<?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
</body>
</html>
