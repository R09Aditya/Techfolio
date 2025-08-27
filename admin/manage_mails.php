<?php
include '../config/db.php';

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM mails WHERE id=$id");
    header("Location: manage_mails.php");
    exit;
}

// Handle Edit Save
if (isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $mail_sent_by = $_POST['mail_sent_by'];
    $mail_sent_to = $_POST['mail_sent_to'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("UPDATE mails SET mail_sent_by=?, mail_sent_to=?, password=? WHERE id=?");
    $stmt->bind_param("sssi", $mail_sent_by, $mail_sent_to, $password, $id);
    $stmt->execute();
    header("Location: manage_mails.php");
    exit;
}

$result = $conn->query("SELECT * FROM mails");
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="admin.css">
    <title>Manage Mails</title>
    <style>
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background: #f2f2f2; }
        form { display: inline; }
    </style>
</head>
<body>
    <button onclick="window.location.href='index.php'" 
        style="padding:8px 16px; border:none; border-radius:6px; background:#007BFF; color:white; cursor:pointer;">
  ⬅ Back to Home
</button>

    <h2>Manage Mails Table</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Mail Sent By</th>
            <th>Mail Sent To</th>
            <th>Password</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <form method="post" action="manage_mails.php">
                <td><?= $row['id']; ?><input type="hidden" name="id" value="<?= $row['id']; ?>"></td>
                <td><input type="text" name="mail_sent_by" value="<?= htmlspecialchars($row['mail_sent_by']); ?>"></td>
                <td><input type="text" name="mail_sent_to" value="<?= htmlspecialchars($row['mail_sent_to']); ?>"></td>
                <td><input type="text" name="password" value="<?= htmlspecialchars($row['password']); ?>"></td>
                <td>
                    <button type="submit" name="update">Update</button>
                    <a href="manage_mails.php?delete=<?= $row['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </form>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
