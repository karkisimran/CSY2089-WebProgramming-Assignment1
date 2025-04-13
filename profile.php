<?php
session_start();
if (!isset($_SESSION["email"])) {
    // Redirect to login if not logged in
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Profile</title>
  <style>
   body {
      margin: 0;
      font-family: "Segoe UI", sans-serif;
      background: linear-gradient(to right,hsl(220, 95.30%, 66.70%),rgb(183, 177, 250));
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .profile-container {
		width: 600px;
		margin: 60px auto;
		padding: 30px;
		background: white;
		border-radius: 10px;
		box-shadow: 0 8px 20px rgba(0,0,0,0.1);
		text-align: center;
    }
	.information{
		text-align: left;
		font-weight:600;
		
	}
	
    .logout-btn {
      margin-top: 20px;
      padding: 10px 20px;
      background-color:rgb(223, 28, 14);
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
    .logout-btn:hover {
      background-color:rgb(132, 28, 28);
    }
  </style>
</head>
<body>

<div class="profile-container">
  <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> 👋</h2>
  <p>You are now in the User Dashboard.</p>
  
    <div class="information">
		<p> Your information:</p>
		<p>Email: <?php echo htmlspecialchars($_SESSION['email']); ?></p>
		<p>Username: <?php echo htmlspecialchars($_SESSION['username']); ?></p>
   </div>

	<form action="logout.php" method="post">
    <button class="logout-btn" type="submit">Logout</button>
  </form>
</div>



</body>
</html>

