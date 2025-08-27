<?php
require_once(__DIR__ . '/../config/db.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Certifications</title>
    <link rel="stylesheet" href="cert.css">
</head>
<body onload="bigger()">
    <div class="cursor"></div> <!-- Custom Cursor -->
    <div class="loader" id="loader">
        <img src="loader.gif" alt="Loading...">
    </div>
    <div class="head">
        <a href="#">
            <img src="me.jpg" class="logo" alt="">
        </a>
        <div class="button">
        <form class="formbtn" action="Index.php"><button class="cs">Home</button></form>
        <form class="formbtn" action="projects.php"><button class="cs">Highlights</button></form>
        <form class="formbtn" action="certifications.php"><button class="workw">Certifications</button></form>
        <form class="formbtn" action="reach.php"><button class="workw">Reach me out!</button></form>
        </div>
    </div>
    <h1 class="hh">My Certifications</h1>
    <div class="certification-list">
        <?php
        $result = $conn->query("SELECT * FROM certifications ORDER BY date_completed DESC");
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo '<div class="certification">';
                if (!empty($row['image'])) {
                    $imgSrc = "" . urlencode($row['image']);
                    echo '<img src="../cert/'. htmlspecialchars($row['image']) .'" alt="Certification image" class="cert-img">';
                }
                echo '<h2>' . htmlspecialchars($row['title']) . '</h2>';
                echo '<p>' . nl2br(htmlspecialchars($row['description'])) . '</p>';
                if (!empty($row['link'])) {
                    echo '<a href="' . htmlspecialchars($row['link']) . '" target="_blank">View Certificate</a>';
                }
                echo '<p class="date">' . date('F Y', strtotime($row['date_completed'])) . '</p>';
                echo '</div>';
            }
        } else {
            echo "<p>No certifications to display yet.</p>";
        }
        ?>
    </div>
    <script src="cert.js"></script>
    <script>
        function bigger()
    {
var foo = setInterval(function () {
    document.getElementById('loader').style.display = 'none';
    // document.getElementById('loader').style.height = 0 + 'px';
}, 1000);
    }
    </script>
    
</body>
</html>
