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

$categoryId = $_GET['id'];

// Optionally: Check if there are auctions under this category before deleting
$stmt = $Connection->prepare("SELECT COUNT(*) FROM auction WHERE categoryId = ?");
$stmt->execute([$categoryId]);
$auctionCount = $stmt->fetchColumn();

if ($auctionCount > 0) {
    echo "Cannot delete category because there are active auctions linked to it.";
    exit();
}

// Delete category
$stmt = $Connection->prepare("DELETE FROM category WHERE category_id = ?");
$stmt->execute([$categoryId]);

header('Location: adminCategories.php');
exit();
?>
<?php include 'includes/footer.php'; ?>
