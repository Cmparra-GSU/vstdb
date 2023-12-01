<?php
session_start();
include('../admin-panel/functions.php'); 
include('../admin-panel/plugin-functions.php'); 
$conn = connect();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VST Database</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Raleway:wght@300;400;500;600;700&display=swap">


  <link rel="stylesheet" href="index.css">
  <script src="index.js"></script>

</head>
<body>
<?php include ('header.php');?>


  
    <div class="main">
        <div class =recent-articles>
        <div class="contact-form">
            <h2>Contact Us</h2>
            <form method="POST" action="send_email.php">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
                <br>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <br>
                <label for="message">Message:</label>
                <textarea id="message" name="message" required></textarea>
                <br>
                <button type="submit">Submit</button>
            </form>
        </div>

        </div>
    </div>
</body>
</html>
