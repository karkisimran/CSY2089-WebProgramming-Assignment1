<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($Connection)) {
    require_once 'db.php';
}

$categories = $Connection->query("SELECT * FROM category ORDER BY category_name ASC")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Carbuy Auctions</title>
    <link rel="stylesheet" href="carbuy.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
<header>
    <h1>
        <span class="C">C</span>
        <span class="a">a</span>
        <span class="r">r</span>
        <span class="b">b</span>
        <span class="u">u</span>
        <span class="y">y</span>
    </h1>

    <form action="#">
        <input type="text" name="search" placeholder="Search for a car" />
        <input type="submit" name="submit" value="Search" />
    </form>
</header>

<nav>
    <ul>
        <?php foreach ($categories as $cat): ?>
            <li><a class="categoryLink" href="categoryPage.php?category=<?= urlencode($cat['category_name']) ?>">
                <?= htmlspecialchars($cat['category_name']) ?>
            </a></li>
        <?php endforeach; ?>

        <?php if (isset($_SESSION['user_id'])): ?>
            <?php if ($_SESSION['is_admin'] == 1): ?>
                <li><a href="adminDashboard.php" style="color: black; font-size: 25px;">
                    <i class="fa-solid fa-user"></i> Admin Dashboard</a></li>
            <?php else: ?>
                <li><a href="profile.php" style="color: black; font-size: 25px;">
                    <i class="fa-solid fa-user"></i> Profile</a></li>
            <?php endif; ?>
        <?php else: ?>
            <li><a class="authLink" href="login.php">Login</a></li>
        <?php endif; ?>
    </ul>
</nav>

