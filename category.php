<?php
session_start();
require_once 'db.php';

$categoryId = $_GET['id'] ?? null;

if (!$categoryId) {
    header("Location: index.php");
    exit();
}

$stmt = $Connection->prepare("SELECT category_name FROM category WHERE category_id = ?");
$stmt->execute([$categoryId]);
$category = $stmt->fetch();

if (!$category) {
    echo "Category not found.";
    exit();
}

$stmt = $Connection->prepare("SELECT * FROM auction WHERE categoryId = ?");
$stmt->execute([$categoryId]);
$auctions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include 'header.php'; ?>
<h2 style="text-align:center;">Cars in <?= htmlspecialchars($category['category_name']) ?> Category</h2>

<div class="auction-container" style="display: flex; flex-wrap: wrap; justify-content: center; gap: 20px; padding: 20px;">
  <?php foreach ($auctions as $auction): ?>
    <?php
      $endDate = new DateTime($auction['endDate']);
      $now = new DateTime();
      $remaining = $now->diff($endDate);
      $remainingTime = $endDate < $now ? 'Auction ended' : $remaining->format('%a days %h hours %i minutes');
    ?>
    <div class="auction-card" style="border: 1px solid #ccc; padding: 15px; width: 300px; border-radius: 8px; background: #f9f9f9;">
      <h3><?= htmlspecialchars($auction['title']) ?></h3>
      <p><?= htmlspecialchars($auction['description']) ?></p>
      <p><strong>Ends:</strong> <?= htmlspecialchars($auction['endDate']) ?></p>
      <p><strong>Time Left:</strong> <?= $remainingTime ?></p>
    </div>
  <?php endforeach; ?>
</div>
    