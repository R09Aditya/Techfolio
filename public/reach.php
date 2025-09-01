<?php
    
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
include '../config/db.php';


// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Save to contact_messages
    $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $message);
    $stmt->execute();
    $stmt->close();

    // Fetch mail credentials from mails table
    $sql = "SELECT mail_sent_by, mail_sent_to, password FROM mails LIMIT 1"; 
    $result = $conn->query($sql);

    if ($result && $row = $result->fetch_assoc()) {
        $mail_user = $row['mail_sent_by'];
        $mail_to   = $row['mail_sent_to'];
        $mail_pass = $row['password'];

        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = $mail_user;
            $mail->Password   = $mail_pass;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Recipients
            $mail->setFrom($mail_user, $name);
            $mail->addAddress($mail_to, 'Admin');

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Portfolio Mail';
            $mail->Body    = 'Name: ' . $name . '<br>Email: ' . $email . '<br>Message: ' . $message;

            $mail->send();
            echo "<script>alert('The message is sent and saved in DB')</script>";
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        echo "No mail credentials found in database.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aditya Sharma - Portfolio</title>
    <link rel="stylesheet" href="reach.css">
    <script src="https://kit.fontawesome.com/08a1d1d5aa.js" crossorigin="anonymous"></script>
    <script src="reach.js"></script>
</head>

<body onload="bigger()">
    <div class="cursor"></div>

    <div class="loader1" id="loader1">
        <img src="loader.gif" alt="Loading...">
    </div>
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

    <div class="contact-section">
        <h2 class="contact-heading">Contact Me</h2>
        <form class="contact-form" method="post" onsubmit="showLoader()">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="message">Message:</label>
            <textarea id="message" name="message" rows="4" required></textarea>

            <button name="submit" type="submit" class="contact-submit">Send Message</button>
        </form>
    </div>

    <div class="end">
        
            <div class="lg">
    <?php
    $socials = $conn->query("SELECT * FROM social_links ORDER BY id ASC");
    while ($s = $socials->fetch_assoc()):
    ?>
        <a href="<?php echo htmlspecialchars($s['url']); ?>" target="_blank">
            <i class="<?php echo htmlspecialchars($s['icon_class']); ?> social-icon"></i>
        </a>
    <?php endwhile; ?>
</div>
    </div>

    <script>
        function showLoader() {
            document.getElementById('loader').style.display = 'flex';
        }
        function bigger() {
            var foo = setInterval(function() {
                document.getElementById('loader1').style.display = 'none';
                // document.getElementById('loader').style.height = 0 + 'px';
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
