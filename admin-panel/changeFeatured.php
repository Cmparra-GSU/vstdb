<?php
include('functions.php'); 
session_start(); 
isAdmin();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["action"]) && $_POST["action"] == "toggleFeatured") {
        $articleID = $_POST["articleID"];
        
        $conn = connect();
        $updateResult = toggleFeaturedArticle($articleID, $conn);
        
        if ($updateResult) {
            echo "Toggle featured status success!";
        } else {
            echo "Error toggling featured status.";
        }
        
        mysqli_close($conn);
    }
}

?>
