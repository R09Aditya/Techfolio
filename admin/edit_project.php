<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
require_once(__DIR__ . '/../config/db.php');

$msg = "";

// Check if ID is provided
if (!isset($_GET['id'])) {
    die("Invalid request. No project ID provided.");
}
$id = intval($_GET['id']);

// Fetch existing project
$stmt = $conn->prepare("SELECT * FROM projects WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("Project not found.");
}
$project = $result->fetch_assoc();
$stmt->close();

// Handle update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $link = $_POST['link'];

    $newImage = $project['image']; // keep old image by default

    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $target_dir = "../project/";
        $target = $target_dir . basename($image);

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            // Delete old image
            $oldPath = "../project/" . $project['image'];
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
            $newImage = $image;
        } else {
            $msg = "Image upload failed. Keeping old image.";
        }
    }

    $stmt = $conn->prepare("UPDATE projects SET title=?, description=?, image=?, link=? WHERE id=?");
    $stmt->bind_param("ssssi", $title, $description, $newImage, $link, $id);

    if ($stmt->execute()) {
        $msg = "Project updated successfully!";
        // refresh data
        $project['title'] = $title;
        $project['description'] = $description;
        $project['link'] = $link;
        $project['image'] = $newImage;
    } else {
        $msg = "Database error: Could not update project.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Project</title>
    <link rel="stylesheet" href="../public/style.css">
    <style>
        .preview-img { max-width: 200px; margin-bottom: 10px; display: block; }
    </style>
</head>
<body>
    <h2>Edit Project</h2>
    <form method="post" enctype="multipart/form-data">
        <label>Title:</label><br>
        <input type="text" name="title" value="<?php echo htmlspecialchars($project['title']); ?>" required><br><br>

        <label>Description:</label><br>
        <textarea name="description" required><?php echo htmlspecialchars($project['description']); ?></textarea><br><br>

        <label>Current Image:</label><br>
        <img src="../project/<?php echo htmlspecialchars($project['image']); ?>" class="preview-img" alt="Project Image"><br>
        <input type="file" name="image"><br><br>

        <label>Project Link:</label><br>
        <input type="url" name="link" value="<?php echo htmlspecialchars($project['link']); ?>"><br><br>

        <button type="submit">Update Project</button>
    </form>

    <p><?php echo htmlspecialchars($msg); ?></p>
    <p><a href="manage_projects.php">⬅ Back to Projects</a></p>
</body>
</html>
