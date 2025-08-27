<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin.css">
    <!-- <link rel="stylesheet" href="../public/style.css"> -->
</head>
<body>
    <h1>Admin Dashboard</h1>
    <a href="certification.php">Certifications</a><br>
    <a href="projects.php">Projects</a>
    <a href="view_messages.php">Messages</a>
    <a href="manage_mails.php">Manage Mails</a>
    <a href="socials.php">Social Links</a>
    <a href="manage_portfolio_text.php">Portfolio Text</a>
    <a href="logout.php">Logout</a>
</body>
</html>
