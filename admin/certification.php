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
    $res = $conn->query("SELECT image FROM certifications WHERE id=$id");
    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $imgPath = "../cert/" . $row['image'];
        if (file_exists($imgPath)) {
            unlink($imgPath);
        }
    }
    $conn->query("DELETE FROM certifications WHERE id=$id");
    $msg = "Certification deleted successfully!";
}

// Handle new upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['title'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $link = $_POST['link'];
    $date_completed = $_POST['date_completed'];
    $image = $_FILES['image']['name'];
    $target_dir = "../cert/";

    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    $target = $target_dir . basename($image);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $stmt = $conn->prepare("INSERT INTO certifications (title, description, image, link, date_completed) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $title, $description, $image, $link, $date_completed);

        if ($stmt->execute()) {
            $msg = "Certification added successfully!";
        } else {
            $msg = "Database error: Could not save certification.";
        }
        $stmt->close();
    } else {
        $msg = "Image upload failed.";
    }
}

$result = $conn->query("SELECT * FROM certifications ORDER BY date_completed DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Certifications</title>
    <link rel="stylesheet" href="admin.css">
    <style>
        .certification { border: 1px solid #ccc; padding: 10px; margin-bottom: 15px; }
        .cert-img { max-width: 200px; display: block; }
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

    <h2>Add New Certification</h2>
    <form method="post" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="Certification Title" required><br>
        <textarea name="description" placeholder="Description" required></textarea><br>
        <input type="date" name="date_completed" required><br>
        <input type="file" name="image" required><br>
        <input type="url" name="link" placeholder="Certificate URL"><br>
        <button type="submit">Upload</button>
    </form>
    <p><?php echo htmlspecialchars($msg); ?></p>
    
    <h2>Current Certifications</h2>
    <div class="certification-list">
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="certification">
                <img src="../cert/<?php echo htmlspecialchars($row['image']); ?>" class="cert-img" alt="Certification image">
                <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                <p><?php echo nl2br(htmlspecialchars($row['description'])); ?></p>
                <a href="<?php echo htmlspecialchars($row['link']); ?>" target="_blank">View Certificate</a>
                <p class="date"><?php echo date('F Y', strtotime($row['date_completed'])); ?></p>
                
                <div class="actions">
                    <a href="edit_cert.php?id=<?php echo $row['id']; ?>" class="edit-btn">Edit</a>
                    <a href="?delete=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this certification?');">Delete</a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

</body>
</html>
