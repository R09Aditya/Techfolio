<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
require_once(__DIR__ . '/../config/db.php');

$msg = "";

// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $res = $conn->query("SELECT image FROM projects WHERE id=$id");
    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $imgPath = "../project/" . $row['image'];
        if (file_exists($imgPath)) {
            unlink($imgPath);
        }
    }
    $conn->query("DELETE FROM projects WHERE id=$id");
    $msg = "Project deleted successfully!";
}

// Handle upload
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['title'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $link = $_POST['link'];
    $image = $_FILES['image']['name'];
    $target_dir = "../project/";  

    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0755, true);
    }
    $target = $target_dir . basename($_FILES['image']['name']);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $stmt = $conn->prepare("INSERT INTO projects (title, description, image, link) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $title, $description, $image, $link);
        if ($stmt->execute()) {
            $msg = "Project uploaded!";
        } else {
            $msg = "Database error: Could not save project.";
        }
    } else {
        $msg = "Image upload failed.";
    }
}

// Fetch projects
$result = $conn->query("SELECT * FROM projects ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Projects</title>
    <link rel="stylesheet" href="admin.css">
    <style>
        .project { border: 1px solid #ccc; padding: 10px; margin-bottom: 15px; }
        .project-img { max-width: 200px; display: block; }
        .actions { margin-top: 10px; }
        .actions a { margin-right: 10px; text-decoration: none; padding: 5px 8px; border-radius: 5px; }
        .edit-btn { background: #007bff; color: white; }
        .delete-btn { background: #dc3545; color: white; }
    </style>
</head>
<body>
<button onclick="window.location.href='index.php'" 
        style="padding:8px 16px; border:none; border-radius:6px; background:#007BFF; color:white; cursor:pointer;">
  ⬅ Back to Home
</button>

    <h2>Add New Project</h2>
    <form method="post" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="Title" required><br>
        <textarea name="description" placeholder="Description" required></textarea><br>
        <input type="file" name="image" required><br>
        <input type="url" name="link" placeholder="Project URL"><br>
        <button type="submit">Upload</button>
        <?php if (!empty($msg)) echo "<p>$msg</p>"; ?>
    </form>

    <h2>Current Projects</h2>
    <div class="project-list">
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="project">
                <img src="../project/<?php echo htmlspecialchars($row['image']); ?>" class="project-img">
                <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                <p><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>
                <a href="<?php echo htmlspecialchars($row['link']); ?>" target="_blank">View Project</a>

                <div class="actions">
                    <a href="edit_project.php?id=<?php echo $row['id']; ?>" class="edit-btn">Edit</a>
                    <a href="?delete=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this project?');">Delete</a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- <script src="reach.js"></script> -->
</body>
</html>
