<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
require_once(__DIR__ . '/../config/db.php');

// Handle add
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $platform = $_POST['platform'];
    $url = $_POST['url'];
    $icon_class = $_POST['icon_class'];

    $stmt = $conn->prepare("INSERT INTO social_links (platform, url, icon_class) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $platform, $url, $icon_class);
    $stmt->execute();
    $stmt->close();
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM social_links WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Fetch socials
$result = $conn->query("SELECT * FROM social_links ORDER BY id ASC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Social Links</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <button onclick="window.location.href='index.php'" 
        style="padding:8px 16px; border:none; border-radius:6px; background:#007BFF; color:white; cursor:pointer;">
  ⬅ Back to Home
</button>

    <h2>Add New Social Link</h2>
    <form method="post">
        <input type="text" name="platform" placeholder="Platform Name" required><br>
        <input type="url" name="url" placeholder="Profile URL" required><br>
        <input type="text" name="icon_class" placeholder="FontAwesome Icon Class" required><br>
        <button type="submit" name="add">Add Social Link</button>
    </form>

    <h2>Current Social Links</h2>
    <table border="1" cellpadding="8">
        <tr>
            <th>Platform</th>
            <th>URL</th>
            <th>Icon</th>
            <th>Action</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['platform']); ?></td>
                <td><a href="<?php echo htmlspecialchars($row['url']); ?>" target="_blank"><?php echo htmlspecialchars($row['url']); ?></a></td>
                <td><i class="<?php echo htmlspecialchars($row['icon_class']); ?>"></i></td>
                <td>
                    <a href="edit_social.php?id=<?php echo $row['id']; ?>">Edit</a> | 
                    <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete this social link?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
