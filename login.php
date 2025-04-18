<?php
require_once 'db.php'; 
session_start();

$error = ''; 

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $username = $_POST['username'] ;
    $password = $_POST['password'] ;

    if (!$username || !$password) {
        $error = "Please enter both username and password.";
    } else {
        $stmt = $Connection->prepare("SELECT * FROM user WHERE username = :username");
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
          $_SESSION['logged-in'] = true;
          $_SESSION['user_id'] = $user['id']; 
          $_SESSION['username'] = $user['username'];
          $_SESSION['email'] = $user['email'];
          $_SESSION['is_admin'] = $user['is_admin']; // store from DB
      
      
          header('Location: index.php');
        exit();
    } else {
        // Show error if login fails
        $error = "Invalid credentials.";
    }
  }
}
?>
    
    

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Login | CarBuy</title>
  <style>
    body {
      margin: 0;
      font-family: "Segoe UI", sans-serif;
      background: linear-gradient(to right,rgb(51, 118, 252),rgb(183, 177, 250));
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .login-box {
      background: hsl(220, 96.40%, 89.20%);
      padding: 30px 25px;
      border-radius: 12px;
      box-shadow: 0 20px 25px rgba(0, 0, 0, 0.1);
      width: 340px;
    }

    .login-box h2 {
      margin-bottom: 20px;
      color: #333;
      text-align: center;
    }

    .login-box label {
      display: block;
      margin: 10px 0 5px;
      font-size: 14px;
      font-weight: 600;
    }

    .login-box input[type="text"],
    .login-box input[type="password"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border-radius: 5px;
      border: 1px solid #ccc;
      box-sizing: border-box;
    }

    .login-box button {
      width: 100%;
      padding: 10px;
      background-color:rgb(40, 157, 235);
      color: white;
      border: none;
      border-radius: 6px;
      font-weight: bold;
      cursor: pointer;
    }

    .login-box button:hover {
      background-color:rgb(0, 61, 217);
    }

    .login-box p {
      margin-top: 15px;
      text-align: center;
      font-size: 14px;
    }

    .login-box a {
      color: #007bff;
      text-decoration: none;
    }

    .login-box a:hover {
      text-decoration: underline;
    }

    .error {
      color: red;
      text-align: center;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>
  <div class="login-box">
    <h2>Welcome Back</h2>
    <?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>
    <form method="POST">
    <label>Username:</label><br>
    <input type="text" name="username" required><br><br>
    <label>Password:</label>
    <input type="password" name="password" required><br>
    <button type="submit">Login</button>
</form>
<?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <p>New here? <a href="register.php">Create an account</a></p>
  </div>
</body>
</html>