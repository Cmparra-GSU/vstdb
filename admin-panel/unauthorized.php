<?php
include('functions.php');
session_start(); 

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unauthorized Access</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>




    <div class="main-content">
        <br><br><br><br><br><br><br><br><br><br><br><br>
    <h1>You Aren't Supposed to Be Here</h1>
<?php
    if (isset($_SESSION['UserID'])) {
        echo '<p>Logged in as: ' . $_SESSION['UserName'] . '</p>';
        echo '<p>User Role: ' . $_SESSION['UserRole'] . '</p>';
        echo '<p>If you think you should have access, or would like access, contact us or apply to be an admin</p>';
        echo '<p>Otherwise, you need to leave.</p>';
    } else {
        echo '<p>You are not logged in.</p>';
    }
    ?>
    <br>
        <a href = "../index/index.php"><img src="funyarinpa.jpg" alt="Get out" width="250px"></a>
    </div>
</body>
</html>
