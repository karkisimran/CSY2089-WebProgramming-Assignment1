<?php
// Load database connection and start session if not already started
require 'db.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ensure the auction ID is present in the URL
if (isset($_GET['auction-id'])) {
    $auctionId = $_GET['auction-id'];

$sql = "
    SELECT a.*, c.category_name, u.username, MAX(b.bid_amount) AS top_bid
    FROM auction a
    JOIN category c ON a.categoryId = c.category_id
    JOIN user u ON a.userId = u.id
    LEFT JOIN bid b ON a.id = b.auction_id
    WHERE a.id = :auction_id
";

$stmt = $Connection->prepare($sql);
$stmt->execute(['auction_id' => $auctionId]);
$auction = $stmt->fetch(PDO::FETCH_ASSOC);

    
$auction = $stmt->fetch(PDO::FETCH_ASSOC); 

if ($auction) {
    // Check if 'end_date' exists in the fetched array
    if (isset($auction['end_date'])) {
        echo "Auction ends on: " . $auction['end_date'];
    } else {
        echo "End date is not available.";
    }

    // Check if 'auction_id' exists in the fetched array
    if (isset($auction['auction_id'])) {
        echo "Auction ID: " . $auction['auction_id'];
    } else {
        echo "Auction ID is not available.";
    }
} else {
    echo "No auction data found.";
}

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Auction Details</title>
    <link rel="stylesheet" href="carbuy.css">
    <style>
        .auction-wrapper {
            max-width: 800px;
            margin: 30px auto;
            padding: 25px;
            background: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0 0 12px rgba(0,0,0,0.15);
            font-family: 'Segoe UI', sans-serif;
        }

        .vehicle-img {
            width: 100%;
            max-height: 400px;
            object-fit: cover;
            margin-bottom: 20px;
            border-radius: 10px;
        }

        .title {
            font-size: 28px;
            color: #333;
            margin-bottom: 10px;
        }

        .category, .description, .ending, .posted-by, .bid-value {
            margin-bottom: 10px;
            font-size: 16px;
        }

        .bid-value {
            font-weight: bold;
        }

        .feedback-section, .bid-section {
            margin-top: 30px;
        }

        .reviews-list {
            padding-left: 20px;
        }

        .no-feedback {
            font-style: italic;
            color: #777;
        }

        .return-link {
            display: inline-block;
            margin-top: 20px;
            background-color: #007bff;
            color: #fff;
            padding: 10px 14px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
        }

        .return-link:hover {
            background-color:rgb(15, 122, 237);
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>

<div class="auction-wrapper">
    <?php if ($auction): ?>
        <?php
            // Save auction creator's user ID for future access (like reviews)
            $_SESSION['auction_owner_id'] = $auction['user_id'];

            // Show image or fallback
            $imageFile = !empty($auction['car_image']) ? $auction['car_image'] : '';
            $imgPath = '/images/auction/' . $imageFile;

            if ($imageFile && file_exists($_SERVER['DOCUMENT_ROOT'] . $imgPath)) {
                echo '<img src="' . $imgPath . '" alt="Auction Car" class="vehicle-img">';
            } else {
                echo '<img src="/car.png" alt="Default Car" class="vehicle-img">';
            }
        ?>

        <h2 class="title"><?= htmlspecialchars($auction['title']) ?></h2>
        <div class="category">Category: <?= htmlspecialchars($auction['category_name']) ?></div>
        <div class="description"><?= nl2br(htmlspecialchars($auction['description'])) ?></div>
        <div class="ending">Ends on: <?= htmlspecialchars($auction['end_date']) ?></div>
        <div class="posted-by">Listed by: <?= htmlspecialchars($auction['username']) ?></div>
        <div class="bid-value">Current Top Bid: £<?= $auction['top_bid'] ?? '0' ?></div>

        <!-- Review Section -->
        <div class="feedback-section">
            <h3>Reviews for <?= htmlspecialchars($auction['username']) ?></h3>
            <?php
            $reviewQuery = 'SELECT review_text FROM review WHERE user_id = :seller_id';
            $reviewStmt = $Connection->prepare($reviewQuery);
            $reviewStmt->bindParam(':seller_id', $auction['user_id'], PDO::PARAM_INT);
            $reviewStmt->execute();
            $userReviews = $reviewStmt->fetchAll(PDO::FETCH_ASSOC);

            if ($userReviews):
                echo '<ul class="reviews-list">';
                foreach ($userReviews as $rev) {
                    echo '<li>' . htmlspecialchars($rev['review_text']) . '</li>';
                }
                echo '</ul>';
            else:
                echo '<p class="no-feedback">This seller has no reviews yet.</p>';
            endif;
            ?>
        </div>

        <!-- Conditional Bid/Review Form -->
        <?php if (isset($_SESSION['logged-in']) && $_SESSION['logged-in'] && $_SESSION['user_id'] !== $auction['user_id']): ?>
            <div class="bid-section">
                <h3>Place a Bid</h3>
                <?php include 'addBid.php'; ?>

                <h3>Leave a Review</h3>
                <?php include 'addReview.php'; ?>
            </div>
        <?php endif; ?>

    <?php else: ?>
        <p style="text-align:center; font-size:18px;">Auction not found or does not exist.</p>
    <?php endif; ?>

    <a href="index.php" class="return-link">← Back to Listings</a>
</div>
</body>
</html>
