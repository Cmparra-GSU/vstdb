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
    <title>Plugin Catalog</title>

    <script src="index.js"></script>
    
    <link rel="stylesheet" href="https:
    <link rel="stylesheet" href="https:
    <link rel="stylesheet" href="index.css">
</head>
<body>
<?php include ('header.php');?>
<h1> UNDER CONSTRUCTION </h1>
    <h2>Welcome, <?php echo $_SESSION["UserName"]; ?>!</h2>

    <?php
    if ($_SESSION["UserRole"] === "webmaster") {
        
        echo "<p>You have full control over the website.</p>";
    } elseif ($_SESSION["UserRole"] === "admin") {
        
        echo "<p>You have admin privileges.</p>";
    } elseif ($_SESSION["UserRole"] === "basic") {
        
        echo "<p>You are a basic user.</p>";
    }

    if ($_SESSION["EmailVerified"] == 0) {
        echo "<p>Please verify your email address.</p>";
    }
    
    ?>

    <a href="logout.php">Logout</a>
</body>
</html>
