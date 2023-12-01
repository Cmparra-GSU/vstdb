<?php
include('functions.php');
session_start(); 
isAdmin();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["action"]) && $_POST["action"] == "toggleVisibility") {
        $pluginID = $_POST["pluginID"];
        $newVisibility = $_POST["newVisibility"];
        
        include('plugin-functions.php');
        
        $updateResult = updatePluginVisibility($pluginID, $newVisibility);
        
        if ($updateResult) {
            echo "Visibility updated successfully!";
        } else {
            echo "Error updating visibility.";
        }
    }
}
?>
