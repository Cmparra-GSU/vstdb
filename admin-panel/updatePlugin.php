<?php
include('functions.php');
include('plugin-functions.php');
session_start();
isAdmin();
$conn = connect();


if (isset($_POST['pluginID'])) {
    $pluginID = $_POST['pluginID'];
} elseif (isset($_GET['pluginID'])) {
    $pluginID = $_GET['pluginID'];
}

if ($pluginID !== null) {
    
    $pluginData = getPluginData($pluginID); 

    if (!$pluginData) {
        echo "Plugin not found or you don't have permission to edit it.";
        exit();
    }
} else {
    echo "Invalid plugin ID.";
    exit();
}

if (isset($_POST['updatePlugin'])) {
    
    $editedTitle = $_POST['editedTitle'];
    $editedDevName = $_POST['editedDevName'];
    $editedDevSite = $_POST['editedDevSite'];
    $editedType = $_POST['type'];
    $editedPrice = floatval($_POST['editedPrice']);
    $editedDL = $_POST['editedDL'];
    $editedDemo = isset($_POST['editedDemo']) ? 1 : 0;
    $editedDate = $_POST['editedDate'];
    $editedLD = $_POST['editedLD'];
    $editedShortDescription = $_POST['editedShortDescription'];
    $editedCatIMG = $_POST['editedCatIMG'];
    $editedCategories = isset($_POST['categories']) ? $_POST['categories'] : []; 
    $editedImageURLs = isset($_POST['pluginImages']) ? $_POST['pluginImages'] : []; 
    
    $updateResult = updatePlugin(
        $pluginID,
        $editedTitle,
        $editedDevName,
        $editedDevSite,
        $editedType,
        $editedCategories,
        $editedPrice,
        $editedDL,
        $editedDemo,
        $editedDate,
        $editedLD,
        $editedShortDescription,
        $editedCatIMG,
        $editedImageURLs,
        $conn
    );

    if (is_numeric($updateResult)) {
        
        echo "Plugin updated successfully. Plugin ID: $updateResult";
        header("refresh:5;url=preview.php?pluginID=" . $pluginID);
    } else {
        
        echo "Error updating the plugin: $updateResult";
    }
}
?>
