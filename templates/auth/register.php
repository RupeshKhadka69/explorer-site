<?php
defined("BASEPATH") or exit("No direct script access allowed");
$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $db = ConnectDb::getInstance();
        $user = new User($db);

        $username = $_POST['username'] ?? "";
        $email = $_POST['email'] ?? "";
        $password = $_POST['password'] ?? "";

        if (empty("username") || empty("email") || empty("password")) {
            $error = "All fields are requred";
        } else if (strlen($password) < 8) {
            $error = "password must be atleast 8 character long";
        } else {
            if ($user->register($username, $email, $password)) {
                header("Location:index.php");
                $success = "register succesfully ! you can now log in";
            } else {
                $error = "Registration failed. username or email might already exits";
            }
        }


    } catch (Exception $e) {
        $error = "An error occured :" . $e->getMessage();
    }
}

include_once dirname(__DIR__) . '/header.php';
?>

<!DOCTYPE html>

<div class="auth-container">
    <h2>Register for Urban Explorer</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <form method="POST" action="" class="auth-form">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username"
                value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                required>
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Register</button>
    </form>

    <div class="auth-footer">
        Already have an account? <a href="index.php?page=login">Login here</a>
    </div>
</div>

<?php
// Include footer
include_once dirname(__DIR__) . '/footer.php';
?>