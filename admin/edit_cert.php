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
    die("Invalid request. No certificate ID provided.");
}
$id = intval($_GET['id']);

// Fetch existing certificate
$stmt = $conn->prepare("SELECT * FROM certifications WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("Certificate not found.");
}
$cert = $result->fetch_assoc();
$stmt->close();

// Handle update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $link = $_POST['link'];
    $date_completed = $_POST['date_completed'];

    $newImage = $cert['image']; // keep old image by default

    if (!empty($_FILES['image']['name'])) {
        $image = $_FILES['image']['name'];
        $target_dir = "../cert/";
        $target = $target_dir . basename($image);

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            // Delete old image
            $oldPath = "../cert/" . $cert['image'];
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
            $newImage = $image;
        } else {
            $msg = "Image upload failed. Keeping old image.";
        }
    }

    $stmt = $conn->prepare("UPDATE certifications SET title=?, description=?, image=?, link=?, date_completed=? WHERE id=?");
    $stmt->bind_param("sssssi", $title, $description, $newImage, $link, $date_completed, $id);

    if ($stmt->execute()) {
        $msg = "Certification updated successfully!";
        // refresh the data
        $cert['title'] = $title;
        $cert['description'] = $description;
        $cert['link'] = $link;
        $cert['date_completed'] = $date_completed;
        $cert['image'] = $newImage;
    } else {
        $msg = "Database error: Could not update certification.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Certification</title>
    <link rel="stylesheet" href="admin.css">
    <style>
        .preview-img { max-width: 200px; margin-bottom: 10px; display: block; }
    </style>
</head>
<body>
    <h2>Edit Certification</h2>
    <form method="post" enctype="multipart/form-data">
        <label>Title:</label><br>
        <input type="text" name="title" value="<?php echo htmlspecialchars($cert['title']); ?>" required><br><br>

        <label>Description:</label><br>
        <textarea name="description" required><?php echo htmlspecialchars($cert['description']); ?></textarea><br><br>

        <label>Date Completed:</label><br>
        <input type="date" name="date_completed" value="<?php echo htmlspecialchars($cert['date_completed']); ?>" required><br><br>

        <label>Current Image:</label><br>
        <img src="../cert/<?php echo htmlspecialchars($cert['image']); ?>" class="preview-img" alt="Certificate Image"><br>
        <input type="file" name="image"><br><br>

        <label>Certificate Link:</label><br>
        <input type="url" name="link" value="<?php echo htmlspecialchars($cert['link']); ?>"><br><br>

        <button type="submit">Update Certification</button>
    </form>

    <p><?php echo htmlspecialchars($msg); ?></p>
    <p><a href="certification.php">⬅ Back to Certifications</a></p>
</body>
</html>
