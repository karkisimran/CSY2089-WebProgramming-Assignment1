<?php
session_start();
require('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $Connection->prepare("SELECT * FROM user WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // If user exists and password matches
    if ($user && password_verify($password, $user['password'])&& $user['is_admin'] == 1) {
        // Set common session variables
        $_SESSION['admin'] = true;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['is_admin'] = $user['is_admin']; // Store admin status

        
        header('Location: adminDashboard.php');
            exit();
        };
        }else {
        $error = "Invalid credentials.";
    }
?>


<form method="POST">
    <input name="username" required placeholder="Admin Username">
    <input type="password" name="password" required placeholder="Password">
    <button type="submit">Login</button>
    <?php if (isset($error)) echo "<p>$error</p>"; ?>
</form>
