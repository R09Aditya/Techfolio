<?php
require_once(__DIR__ . '/../config/db.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM admin WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    // Check username exists
    if ($stmt->num_rows == 1) {
        $stmt->bind_result($admin_id, $hash);
        $stmt->fetch();
        if (password_verify($password, $hash)) {
            $_SESSION['admin_id'] = $admin_id;
            header("Location: index.php");
            exit;
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "Invalid username.";
    }
}
?>
<!-- Login Form HTML -->
<!DOCTYPE html>
<html>
<head><title>Admin Login</title><link rel="stylesheet" href=""></head>
<body>
    <form method="post">
        <h2>Admin Login</h2>
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Login</button>
        <?php if (isset($error)) echo "<p>$error</p>"; ?>
    </form>
</body>
</html>
