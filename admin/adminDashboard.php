<?php
session_start();

// Redirect if not an admin
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== 1) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin Dashboard | CarBuy</title>
  <style>
    body {
      margin: 0;
      font-family: "Segoe UI", sans-serif;
      background: #f0f2f5;
    }

    .dashboard {
      max-width: 700px;
      margin: 60px auto;
      padding: 30px;
      background: white;
      border-radius: 10px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.1);
      text-align: center;
    }

    h1 {
      color: #333;
    }

    .buttons {
      margin-top: 30px;
      display: flex;
      flex-direction: column;
      gap: 15px;
      align-items: center;
    }

    .buttons a {
      display: inline-block;
      padding: 12px 25px;
      background-color: #007bff;
      color: white;
      text-decoration: none;
      font-weight: bold;
      border-radius: 5px;
      transition: background-color 0.2s ease;
    }

    .buttons a:hover {
      background-color: #0056b3;
    }

    .logout {
      margin-top: 40px;
      color: #777;
    }

    .logout a {
      color: #d11a2a;
      text-decoration: none;
      font-weight: bold;
    }

    .logout a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <div class="dashboard">
    <h1>Welcome, Admin 👋</h1>
    <p>You are now in the Admin Dashboard.</p>

    <div class="buttons">
      <a href="adminCategories.php">Manage Categories</a>
      <a href="manageAuctions.php">Manage Auctions</a>
      <a href="adminUsers.php">Manage Users</a> <!-- optional -->
    </div>

    <div class="logout">
      <p><a href="logout.php">Log Out</a></p>
    </div>
  </div>

</body>
</html>
