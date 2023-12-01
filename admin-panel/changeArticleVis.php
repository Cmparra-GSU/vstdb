<?php
include('functions.php'); 
session_start(); 
isAdmin();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["action"]) && $_POST["action"] == "toggleVisibility") {
        $articleID = $_POST["articleID"];

        $conn = connect();

        $updateResult = toggleArticleVisibility($articleID, $conn, 'toggleVisibility');

        if ($updateResult) {
            echo "Toggle visibility status success!";
        } else {
            echo "Error toggling visibility status.";
        }

        mysqli_close($conn);
    }
}
?>
