<?php
require 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = $_POST['reviewContent'] ?? '';
    $reviewee_id = $_POST['reviewee_id'] ?? '';
    $reviewer_id = $_SESSION['user_id'] ?? null;
    $redirect = $_POST['redirect'] ?? 'index.php';

    if ($reviewer_id && $reviewee_id && !empty($content)) {
        $stmt = $Connection->prepare("INSERT INTO review (reviewer_id, reviewee_id, content, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$reviewer_id, $reviewee_id, $content]);
    }

    header("Location: " . $redirect);
    exit;
}
?>
