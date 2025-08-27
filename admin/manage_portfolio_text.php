<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
require_once(__DIR__ . '/../config/db.php');

// Fetch current text
$stmt = $conn->query("SELECT * FROM portfolio_text LIMIT 1");
$current = $stmt->fetch_assoc();

// Update text
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $headline = $_POST['headline'];
    $subheadline = $_POST['subheadline'];

    if ($current) {
        $stmt = $conn->prepare("UPDATE portfolio_text SET headline=?, subheadline=? WHERE id=?");
        $stmt->bind_param("ssi", $headline, $subheadline, $current['id']);
    } else {
        $stmt = $conn->prepare("INSERT INTO portfolio_text (headline, subheadline) VALUES (?, ?)");
        $stmt->bind_param("ss", $headline, $subheadline);
    }

    if ($stmt->execute()) {
        $msg = "Portfolio text updated successfully!";
    } else {
        $msg = "Error updating text.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Portfolio Text</title>
</head>
<body>
    <button onclick="window.location.href='index.php'" 
        style="padding:8px 16px; border:none; border-radius:6px; background:#007BFF; color:white; cursor:pointer;">
  ⬅ Back to Home
</button>

    <h2>Manage Portfolio Text</h2>
    <?php if (!empty($msg)) echo "<p style='color:green;'>$msg</p>"; ?>

    <form method="POST">
        <label>Headline:</label><br>
        <input type="text" name="headline" value="<?= htmlspecialchars($current['headline'] ?? '') ?>" required><br><br>

        <label>Subheadline:</label><br>
        <textarea name="subheadline" rows="5" required><?= htmlspecialchars($current['subheadline'] ?? '') ?></textarea><br><br>

        <button type="submit">Save</button>
    </form>

    <br>
    <a href="dashboard.php">Back to Dashboard</a>
</body>
</html>
