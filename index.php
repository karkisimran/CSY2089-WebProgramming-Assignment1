<?php
require 'db.php';
include 'includes/header.php';

// Check if user is logged in
if (!isset($_SESSION['logged-in']) || !$_SESSION['logged-in']) {
    header('Location: login.php');
    exit();
}

// Fetch user-specific auctions if not admin
$auctions = [];
if ($_SESSION['is_admin'] == 0) {
    $stmt = $Connection->prepare("SELECT * FROM auction WHERE userId = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $auctions = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home - Car Auction</title>
    <link rel="stylesheet" href="carbuy.css">
    <style>
        .auction-box {
            border: 1px solid #ccc;
            padding: 1vw;
            margin: 1vw auto;
            max-width: 800px;
            border-radius: 10px;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
            font-family: 'Segoe UI', sans-serif;
        }

        .auction-box h3 {
            font-size: 1.5em;
            color: #333;
        }

        .auction-box p {
            font-size: 1em;
            margin: 0.5em 0;
            color: #444;
        }

        .auction-box a {
            display: inline-block;
            margin-top: 10px;
            font-weight: bold;
            text-decoration: none;
            color: #007bff;
        }

        .auction-box a:hover {
            color: #0056b3;
        }

        h2 {
            text-align: center;
            font-size: 2em;
            margin-top: 30px;
        }

        .auction-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 40px auto;
            gap: 20px;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
        }

        .auction-actions a {
            display: inline-block;
            margin: 0 10px;
            padding: 8px 16px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }

        .auction-actions a:hover {
            background-color: rgb(46, 129, 216);
        }
    </style>
</head>
<body>

    <h1>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</h1>

    <!-- Show different content based on the role -->
    <?php if ($_SESSION['is_admin'] == 1): ?>
        <!-- Admin Dashboard Link -->
        <h2>Admin Dashboard</h2>
        <a href="adminDashboard.php">Go to Admin Dashboard</a>
    <?php else: ?>
        <!-- User Auctions -->
        <h2>Your Auctions</h2>

        <?php if (isset($_SESSION['user_id']) && $_SESSION['is_admin'] == 0): ?>
            <div style="text-align: center; margin: 20px 0;">
                <a href="postAuction.php" style="padding: 10px 20px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 5px;">
                    Post Auction
                </a>
            </div>
        <?php endif; ?>

        <div class="auction-container">
            <?php foreach ($auctions as $auction): ?>
                <div style="text-align: center; margin: 20px 0;">
                    <h3><?= htmlspecialchars($auction['title']) ?></h3>
                    <p><strong>Ends:</strong> <?= htmlspecialchars($auction['endDate']) ?></p>
                    <div class="auction-actions">
                        <a href="editAuction.php?id=<?= $auction['id'] ?>">Edit</a>
                        <a href="deleteAuction.php?id=<?= $auction['id'] ?>" onclick="return confirm('Are you sure you want to delete this auction?');">Delete</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <h2>Top 10 Auctions Ending Soon</h2>

<ul class="carList">
    <?php foreach ($auctions as $auction): ?>
        <li>
            <img src="images/auction<?= htmlspecialchars($auction['image'] ?? 'default.jpg') ?>" alt="<?= htmlspecialchars($auction['title'] ?? 'Car') ?>">
            <article>
                <h2><?= htmlspecialchars($auction['title'] ?? 'Car model and make') ?></h2>
                <h3><?= htmlspecialchars($auction['category_name'] ?? 'Car category') ?></h3>
                <p><?= htmlspecialchars($auction['description'] ?? 'No description available.') ?></p>
                <p class="price">Current bid: £<?= htmlspecialchars($auction['current_bid'] ?? '0') ?></p>
                <a href="auction.php?auction-id=<?= $auction['id'] ?>" class="more auctionLink">More &gt;&gt;</a>
            </article>
        </li>
    <?php endforeach; ?>
</ul>

</body>
</html>
