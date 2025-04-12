<?php
require 'db.php';

// Fetch the top 10 auctions ending soon with category and user info
$sql = "
    SELECT a.*, c.category_name, u.username,
    (SELECT MAX(bid_amount) FROM bid WHERE auction_id = a.id) AS current_bid
    FROM auction a
    JOIN category c ON a.categoryId = c.category_id
    JOIN user u ON a.userId = u.id
    ORDER BY a.endDate ASC
    LIMIT 10
";

$stmt = $Connection->query($sql);
$auctions = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
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
    </style>
</head>
<body>
<?php include 'header.php'; ?>

<h2>Top 10 Auctions Ending Soon</h2>

<?php foreach ($auctions as $auction): ?>
    <div class="auction-box">
        <h3><?= htmlspecialchars($auction['title']) ?></h3>
        <p><strong>Category:</strong> <?= htmlspecialchars($auction['category_name']) ?></p>
        <p><?= htmlspecialchars($auction['description']) ?></p>
        <p><strong>Ends on:</strong> <?= htmlspecialchars($auction['endDate']) ?></p>
        <p><strong>Posted by:</strong> <?= htmlspecialchars($auction['username']) ?></p>
        <p><strong>Current Bid:</strong> £<?= htmlspecialchars($auction['current_bid'] ?? '0') ?></p>
        <a href="auction.php?auction-id=<?= $auction['id'] ?>">More &gt;&gt;</a>
    </div>
<?php endforeach; ?>

</body>
</html>
