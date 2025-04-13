<?php
// Always start session before any output
session_start();
require 'db.php';

$auction = null;
$reviews = [];

if (isset($_GET['auction-id'])) {
    $auctionId = $_GET['auction-id'];

    // Fetch auction details
    $query = "
        SELECT a.*, c.category_name, u.username, MAX(b.bid_amount) AS top_bid
        FROM auction a
        JOIN category c ON a.categoryId = c.category_id
        JOIN user u ON a.userId = u.id
        LEFT JOIN bid b ON a.id = b.auction_id
        WHERE a.id = :auction_id
    ";
    $stmt = $Connection->prepare($query);
    $stmt->bindParam(':auction_id', $auctionId, PDO::PARAM_INT);
    $stmt->execute();
    $auction = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($auction) {
        $_SESSION['auction_owner_id'] = $auction['userId'];

        // Fetch reviews for the auction's seller
        $reviewQuery = 'SELECT content FROM review WHERE reviewee_id = :seller_id';
        $reviewStmt = $Connection->prepare($reviewQuery);
        $reviewStmt->execute(['seller_id' => $auction['userId']]);
        $reviews = $reviewStmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($auction['title'] ?? 'Auction Details') ?></title>
    <link rel="stylesheet" href="carbuy.css">
</head>
<body>

<?php include 'includes/header.php'; ?>

<div class="auction-wrapper">
    <?php if ($auction): ?>
        <?php
          $imgPath = (!empty($auction['image']) && file_exists("images/auction/" . $auction['image']))
          ? 'images/auction/' . htmlspecialchars($auction['image'])
          : 'images/auction/Porsche991.jpg';
      
        ?>
        <img src="<?= $imgPath ?>" class="vehicle-img auction-img" alt="Auction Image">

        <div class="auction-container">
            <h2><?= htmlspecialchars($auction['title']) ?></h2>
            <p><strong>Category:</strong> <?= htmlspecialchars($auction['category_name']) ?></p>
            <p><?= htmlspecialchars($auction['description']) ?></p>
            <p><strong>Ends on:</strong> <?= htmlspecialchars($auction['endDate']) ?></p>
            <p><strong>Listed by:</strong> <?= htmlspecialchars($auction['username']) ?></p>
            <p><strong>Top Bid:</strong> £<?= htmlspecialchars($auction['top_bid'] ?? 0) ?></p>

            <!-- Feedback / Reviews Section -->
            <div class="feedback-section">
                <h3>Reviews for <?= htmlspecialchars($auction['username']) ?></h3>
                <?php if (!empty($reviews)): ?>
                    <ul class="reviews-list">
                        <?php foreach ($reviews as $rev): ?>
                            <li><?= htmlspecialchars($rev['content']) ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="no-feedback">No reviews yet.</p>
                <?php endif; ?>
            </div>

            <!-- User Bidding & Review Section -->
            <?php if (isset($_SESSION['user_id']) && $_SESSION['is_admin'] == 0): ?>
                <!-- Bid Form -->
                <form method="POST" action="addBid.php" class="bid-form">
                    <h3>Place a Bid</h3>
                    <label for="bid">Your Bid (£):</label>
                    <input type="number" name="bid" min="0.01" step="0.01" required>
                    <input type="hidden" name="auction_id" value="<?= $auction['id'] ?>">
                    <input type="hidden" name="redirect" value="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>">
                    <button type="submit">Place Bid</button>
                </form>

                <!-- Bid History -->
                <div class="bid-history">
                    <h3>Bid History</h3>
                    <?php
                    // Fetch bid history (latest first)
                    $bidHistoryStmt = $Connection->prepare("
                        SELECT b.bid_amount, b.bid_date, u.username
                        FROM bid b
                        JOIN user u ON b.user_id = u.id
                        WHERE b.auction_id = :auction_id
                        ORDER BY b.bid_date DESC
                    ");
                    $bidHistoryStmt->execute(['auction_id' => $auction['id']]);
                    $bids = $bidHistoryStmt->fetchAll(PDO::FETCH_ASSOC);
                    ?>

                    <?php if (!empty($bids)): ?>
                        <ul class="bid-list">
                            <?php foreach ($bids as $bid): ?>
                                <li>
                                    £<?= htmlspecialchars($bid['bid_amount']) ?> by 
                                    <?= htmlspecialchars($bid['username']) ?> 
                                    on <?= date('j M Y, H:i', strtotime($bid['bid_date'])) ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="no-bids">No bids yet.</p>
                    <?php endif; ?>
                </div>

                <!-- Review Form -->
                <?php if (isset($_SESSION['logged-in']) && $_SESSION['logged-in']): ?>
                    <form method="POST" action="submitReview.php">
                        <h3>Leave a Review</h3>
                        <textarea name="reviewContent" required></textarea>
                        <input type="hidden" name="reviewee_id" value="<?= htmlspecialchars($auction['userId']) ?>">
                        <input type="hidden" name="redirect" value="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>">
                        <button type="submit">Submit Review</button>
                    </form>
                <?php else: ?>
                    <p>You must be logged in to submit a review. 
                        <a href="login.php?redirect=<?= urlencode($_SERVER['REQUEST_URI']) ?>">Login here</a>.
                    </p>
                <?php endif; ?>
            <?php endif; ?>

            <a class="back-link" href="listings.php">← Back to Listings</a>
        </div>
    <?php else: ?>
        <p>Sorry, this auction does not exist or has been removed.</p>
    <?php endif; ?>
</div>

</body>
</html>
