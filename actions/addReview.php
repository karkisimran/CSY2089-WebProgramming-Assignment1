<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['review_content'], $_POST['auction_id'])) {

    // Check if user is logged in and not an admin
    if (!isset($_SESSION['user_id'])) {
        echo "<p style='color: red;'>You must be logged in to submit a review.</p>";
        return;
    }

    if ($_SESSION['is_admin'] == 1) {
        echo "<p style='color: red;'>Admins cannot submit reviews.</p>";
        return;
    }

    $reviewerId = $_SESSION['user_id'];
    $auctionId = (int)$_POST['auction_id'];
    $content = trim($_POST['review_content']);

    // Get auction owner
    $stmt = $Connection->prepare("SELECT userId FROM auction WHERE id = ?");
    $stmt->execute([$auctionId]);
    $auction = $stmt->fetch();

    if ($auction) {
        $revieweeId = $auction['userId'];

        // Insert review
        $stmt = $Connection->prepare("INSERT INTO review (reviewer_id, reviewee_id, content, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$reviewerId, $revieweeId, $content]);

        echo "<p style='color: green;'>Review submitted successfully.</p>";
    } else {
        echo "<p style='color: red;'>Invalid auction.</p>";
    }

} else {
    echo "<p style='color: red;'>Invalid request.</p>";
}
