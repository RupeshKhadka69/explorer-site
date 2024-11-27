<?php
defined("BASEPATH") OR exit("No direct script access allowed");

// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    header('Location:index.php');
    exit();
}
$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    try {
        $db = ConnectDb::getInstance();
        $user = new User($db);

        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];

        if (empty($email) || empty($password)) {
            $error = "Email and password are required.";
        } else {
            $user_id = $user->login($email, $password);

            if ($user_id) {
                $_SESSION['user_id'] = $user_id;
                header("Location:index.php");
                exit();
            } else {
                $error = "Invalid email or password.";
            }
        }
    } catch (Exception $e) {
        $error = "An error occurred. Please try again later.";
        // Optionally log $e->getMessage()
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - Urban Explorer</title>
</head>
<body>
    <h2>Login</h2>
    
    <?php if ($error): ?>
        <div style="color: red;"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <form method="POST" action="">
        <div>
            <label>Email:</label>
            <input type="email" name="email" required>
        </div>
        <div>
            <label>Password:</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit">Login</button>
    </form>
    <div class="auth-footer">
        Already have an account? <a href="index.php?page=register">Register here</a>
    </div>
</body>
</html>
