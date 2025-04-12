<?php
require('db.php');

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? '');
  $username = trim($_POST['username'] ?? '');
  $password = $_POST['password'] ?? '';
  $confirmPassword = $_POST['confirm_password'] ?? '';

  // Check for empty fields
  if (!$email || !$username || !$password || !$confirmPassword) {
    $errors[] = "All fields are required.";
  }

  // Validate email
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
  }

  // Check password match
  if ($password !== $confirmPassword) {
    $errors[] = "Passwords do not match.";
  }

  // Check if email or username already exists
  $check = $Connection->prepare("SELECT COUNT(*) FROM user WHERE email = :email OR username = :username");
  $check->execute([':email' => $email, ':username' => $username]);
  $exists = $check->fetchColumn();

  if ($exists > 0) {
    $errors[] = "Email or username already in use.";
  }

  // If no errors, insert user
  if (empty($errors)) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $Connection->prepare(
      'INSERT INTO user (email, username, password) VALUES (:email, :username, :password)'
    );
    echo "User registered!";

    $stmt->execute([
      ':email' => $email,
      ':username' => $username,
      ':password' => $hashedPassword,
    ]);

    echo "<script>
            alert('Registration successful! Redirecting to login...');
            window.location.href = 'login.php';
          </script>";
    exit();
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Register - Carbuy Auctions</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <style>
    body {
      background: linear-gradient(to right,rgb(183, 177, 250),rgb(51, 118, 252));
      font-family: 'Segoe UI', sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }

    .container {
      background: hsl(220, 96.40%, 89.20%);
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0px 50px 26px rgba(9, 9, 9, 0.15);
      width: 320px;
    }

    h2 {
      text-align: center;
      color: #333;
      margin-bottom: 15px;
    }

    label {
      display: block;
      margin-top: 10px;
      font-weight: 600;
    }

    input {
      width: 100%;
      padding: 8px;
      margin-top: 5px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }

    button {
      width: 100%;
      background:rgb(40, 157, 235);
      color: white;
      padding: 10px;
      border: none;
      margin-top: 15px;
      border-radius: 5px;
      font-weight: bold;
      cursor: pointer;
    }

    button:hover {
      background-color:rgb(0, 61, 217);
    }

    .link {
      text-align: center;
      margin-top: 15px;
    }

    .link a {
      color:rgb(88, 74, 245);
      text-decoration: none;
    }

    .link a:hover {
      text-decoration: underline;
    }

    .error {
      background-color: #ffe0e0;
      border-left: 5px solid red;
      padding: 10px;
      margin-bottom: 10px;
      color: #a00;
    }
  </style>
  <script>
    function validateForm() {
      const password = document.getElementById('password').value;
      const confirm = document.getElementById('confirm_password').value;
      if (password !== confirm) {
        alert("Passwords do not match.");
        return false;
      }
      return true;
    }
  </script>
</head>

<body>
  <div class="container">
    <h2>Register</h2>

    <?php if (!empty($errors)): ?>
      <div class="error">
        <?php foreach ($errors as $e) echo "<p>$e</p>"; ?>
      </div>
    <?php endif; ?>

    <form method="post" onsubmit="return validateForm();">
      <label for="email">Email</label>
      <input type="email" name="email" id="email" required />

      <label for="username">Username</label>
      <input type="text" name="username" id="username" required />

      <label for="password">Password</label>
      <input type="password" name="password" id="password" required />

      <label for="confirm_password">Confirm Password</label>
      <input type="password" name="confirm_password" id="confirm_password" required />

      <button type="submit">Create Account</button>
    </form>

    <div class="link">
      Already registered? <a href="login.php">Login here</a>
    </div>
  </div>
</body>
</html>
