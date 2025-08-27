<?php
require_once(__DIR__ . '/../config/db.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Projects</title>
    <link rel="stylesheet" href="style.css">
</head>
<body onload="bigger()">
    <div class="cursor"></div> <!-- Custom Cursor -->
    <!--  -->
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
    <h1 class="hh">My Projects</h1>
    <div class="project-list">
        <?php
        $result = $conn->query("SELECT * FROM projects ORDER BY created_at DESC");
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo '<div class="project">';
                if (!empty($row['image'])) {
                    echo '<img src="../project/' . htmlspecialchars($row['image']) . '" alt="Project image" class="project-img">';
                }
                echo '<h2>' . htmlspecialchars($row['title']) . '</h2>';
                echo '<p>' . nl2br(htmlspecialchars($row['description'])) . '</p>';
                if (!empty($row['link'])) {
                    echo '<a href="' . htmlspecialchars($row['link']) . '" target="_blank">View Project</a>';
                }
                echo '</div>';
            }
        } else {
            echo "<p>No projects to display yet.</p>";
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
