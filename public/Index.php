<?php
include '../config/db.php';

// Fetch portfolio text
$result = $conn->query("SELECT * FROM portfolio_text LIMIT 1");
$portfolio = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Portfolio</title>
    <script src="https://kit.fontawesome.com/08a1d1d5aa.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">
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

    <div class="large">
       <video autoplay muted class="vid">
           <source src="bgvideo.mp4"></source>
       </video>
       <div class="texts">
           <h1 class="hh"><?= htmlspecialchars($portfolio['headline'] ?? 'Hi! I am Aditya Sharma'); ?></h1>
           <h2 class="br"><?= htmlspecialchars($portfolio['subheadline'] ?? 'A 12th-grade student passionate about ethical hacking...'); ?></h2>
       </div>
    </div>

    <script src="reach.js"></script>
    <script>
        function bigger() {
            setTimeout(function() {
                document.getElementById('loader').style.display = 'none';
            }, 1000);
        }

        // Prevent all wheel + touch scroll
window.addEventListener('scroll', () => {
  window.scrollTo(0, 0);
});

window.addEventListener('touchmove', e => {
  e.preventDefault();
}, { passive: false });

window.addEventListener('wheel', e => {
  e.preventDefault();
}, { passive: false });


    </script>
</body>
</html>
