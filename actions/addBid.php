<?php
require 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'] ?? null;
    $auctionId = $_POST['auction_id'] ?? null;
    $bidAmount = $_POST['bid'] ?? null;
    $redirect = $_POST['redirect'] ?? 'index.php';

    if ($userId && $auctionId && is_numeric($bidAmount)) {
        // Get current top bid for this auction
        $stmt = $Connection->prepare("SELECT MAX(bid_amount) as current_bid FROM bid WHERE auction_id = ?");
        $stmt->execute([$auctionId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $currentBid = $row['current_bid'] ?? 0;

        // Optional: Ensure auction isn't expired (based on endDate)
        $auctionStmt = $Connection->prepare("SELECT endDate FROM auction WHERE id = ?");
        $auctionStmt->execute([$auctionId]);
        $auction = $auctionStmt->fetch(PDO::FETCH_ASSOC);
        $now = date('Y-m-d H:i:s');
        if ($auction && $auction['endDate'] < $now) {
            header("Location: $redirect?error=Auction+ended");
            exit;
        }

        if ($bidAmount > $currentBid) {
            $insert = $Connection->prepare("INSERT INTO bid (auction_id, user_id, bid_amount, bid_date) VALUES (?, ?, ?, NOW())");
            $insert->execute([$auctionId, $userId, $bidAmount]);
        } else {
            header("Location: $redirect?error=Bid+must+be+higher+than+current+bid");
            exit;
        }
    }

    header("Location: $redirect");
    exit;
}
?>
