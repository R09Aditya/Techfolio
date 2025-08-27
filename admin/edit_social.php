<?php
require '../config/db.php';

// Check if id is provided
if (!isset($_GET['id'])) {
    die("Invalid Request");
}

$id = intval($_GET['id']);

// Fetch existing record
$stmt = $pdo->prepare("SELECT * FROM social_links WHERE id = ?");
$stmt->execute([$id]);
$social = $stmt->fetch();

if (!$social) {
    die("Record not found!");
}

// Update record if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $platform = trim($_POST['platform']);
    $url = trim($_POST['url']);

    if (!empty($platform) && !empty($url)) {
        $stmt = $pdo->prepare("UPDATE social_links SET platform = ?, url = ? WHERE id = ?");
        $stmt->execute([$platform, $url, $id]);

        header("Location: manage_social.php?success=1");
        exit();
    } else {
        $error = "Both fields are required.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Social Link</title>
</head>
<body>
    
    <h2>Edit Social Link</h2>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form method="POST">
        <label>Platform:</label><br>
        <input type="text" name="platform" value="<?= htmlspecialchars($social['platform']) ?>" required><br><br>

        <label>URL:</label><br>
        <input type="url" name="url" value="<?= htmlspecialchars($social['url']) ?>" required><br><br>

        <button type="submit">Update</button>
    </form>

    <br>
    <a href="manage_social.php">Back</a>
</body>
</html>
