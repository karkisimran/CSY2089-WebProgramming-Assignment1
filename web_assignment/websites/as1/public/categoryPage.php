<?php
require('dbconnect.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$category = isset($_GET['category']) ? htmlspecialchars($_GET['category'], ENT_QUOTES, 'UTF-8') : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $category ?></title>
    <link rel="stylesheet" href="carbuy.css" />
    <style>
        .back-home {
            display: block;
            margin-top: 20px;
            margin-left: auto;
            margin-right: auto;
            background-color: #007bff;
            padding: 10px 20px;
            color: white;
            font-weight: bold;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }

        .no-results {
            text-align: center;
            font-size: 2em;
            font-style: italic;
            padding: 40px;
            color: #666;
        }

        .action-btn {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 4px;
            color: #fff;
            font-weight: bold;
            margin-right: 8px;
            text-decoration: none;
        }

        .edit-btn {
            background-color: #28a745;
        }

        .delete-btn {
            background-color: #dc3545;
        }

        .edit-btn:hover {
            background-color: #1e7e34;
        }

        .delete-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
<?php include('header.php'); ?>
<main>
    <?php
    $catQuery = "SELECT category_id FROM category WHERE category_name = ?";
    $catStmt = $Connection->prepare($catQuery);
    $catStmt->execute([$category]);
    $catId = $catStmt->fetchColumn();

    if ($catId) {
        $auctionQuery = "
            SELECT a.auction_id, a.title, a.description, a.end_date, a.car_image,
                   u.username, c.category_name, COALESCE(MAX(b.bid_amount), 0) as bid_value
            FROM auction a
            LEFT JOIN user u ON a.user_id = u.user_id
            LEFT JOIN category c ON a.category_id = c.category_id
            LEFT JOIN bid b ON a.auction_id = b.auction_id
            WHERE a.category_id = ? AND a.end_date > NOW()
            GROUP BY a.auction_id
            ORDER BY a.end_date ASC;
        ";
        $auctionStmt = $Connection->prepare($auctionQuery);
        $auctionStmt->execute([$catId]);

        if ($auctionStmt->rowCount() > 0) {
            echo '<h1>Category Listings: ' . $category . '</h1>';
            echo '<ul class="carList">';

            while ($item = $auctionStmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<li>';

                $imgSrc = (!empty($item['car_image']) && file_exists($_SERVER['DOCUMENT_ROOT'] . '/images/auction/' . $item['car_image']))
                    ? '/images/auction/' . $item['car_image']
                    : '/car.png';

                echo '<img src="' . $imgSrc . '" alt="' . htmlspecialchars($item['title']) . '">';
                echo '<article>';
                echo '<h2><strong>' . $item['title'] . '</strong></h2>';
                echo '<h4>Category: ' . $item['category_name'] . '</h4>';
                echo '<p>' . $item['description'] . '</p>';
                echo '<p><strong>End Date:</strong> ' . $item['end_date'] . '</p>';
                echo '<p>Posted by: ' . $item['username'] . '</p>';
                echo '<p class="price">Current Bid: £' . $item['bid_value'] . '</p>';

                if (!empty($_SESSION['logged-in']) && $_SESSION['username'] === $item['username']) {
                    echo '<a class="action-btn edit-btn" href="editAuction.php?auction-id=' . $item['auction_id'] . '">Edit</a>';
                    echo '<a class="action-btn delete-btn" href="deleteAuction.php?auction-id=' . $item['auction_id'] . '">Delete</a>';
                }

                echo '<a class="more auctionLink" href="viewAuction.php?auction-id=' . $item['auction_id'] . '">More &gt;&gt;</a>';
                echo '</article>';
                echo '</li>';
            }

            echo '</ul>';
        } else {
            echo '<p class="no-results">No auctions available in this category.</p>';
        }
    } else {
        echo '<p class="no-results">Invalid category specified.</p>';
    }
    ?>
    <a class="back-home" href="index.php">← Back to Home</a>
</main>
</body>
</html>
